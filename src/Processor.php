<?php


namespace StampFilenamesProcessor;

use Keboola\Component\UserException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class Processor
{
    public const PLACEMENT_APPEND = 'append';

    public const PLACEMENT_PREPEND = 'prepend';

    /** @var  string */
    private $format;

    /** @var  string */
    private $placement;

    public function __construct(string $format, string $placement)
    {
        $this->format = $format;
        $this->placement = $placement;
    }

    public function stampNames(string $datadir, string $type): self
    {
        return $this->processFiles(
            sprintf("%s/in/%ss", $datadir, $type),
            sprintf("%s/out/%ss", $datadir, $type)
        );
    }

    private function ensureDir(string $dirPath, string $filePath): bool
    {
        $matches = [];
        // parse directory from file path
        preg_match('/^(.*\/)?[^\/]*$/', $filePath, $matches);
        if(isset($matches[1]) && $matches[1] !== '') {
            $path = sprintf('%s/%s', $dirPath, $matches[1]);
            if(!file_exists($path)) {
                return mkdir($path, 0777, true);
            } else if(!is_dir($path)) {
                return false;
            }
        }
        return true;
    }

    private function processFiles(string $inputDir, string $outputDir): self
    {
        $finder = new Finder();
        $finder->files()->in($inputDir);
        $fs = new FileSystem();
        $stamp = date($this->format);
        foreach($finder as $file) {
            $renamedFilename = $this->rename($file->getRelativePathname(), $stamp);
            if($this->ensureDir($outputDir, $renamedFilename)) {
                // $fs->rename($file->getPathname(), sprintf('%s/%s', $outDirPath, $renamedFilename));
                // use copy to pass tests: rename() cannot rename files to vfs://
                $fs->copy($file->getPathname(), sprintf('%s/%s', $outputDir, $renamedFilename));
            }
        }
        return $this;
    }

    private function rename(string $filePath, string $stamp): string
    {
        switch ($this->placement) {
            case self::PLACEMENT_APPEND:
                return sprintf('%s%s', $stamp, $filePath);
            case self::PLACEMENT_PREPEND:
                return sprintf('%s%s', $filePath, $stamp);
            default:
                throw new UserException(sprintf('Invalid placement parameter [%s]', $this->placement));
        }

    }
}
