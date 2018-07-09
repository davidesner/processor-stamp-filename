<?php

declare(strict_types=1);

namespace StampFilenamesProcessor;

use Keboola\Component\BaseComponent;

class Component extends BaseComponent
{
    public function run(): void
    {
        $processor = new Processor(
            $this->getConfig()->getValue(['parameters', 'format']),
            $this->getConfig()->getValue(['parameters', 'placement'])
        );

        $processor->stampNames($this->getDataDir(), $this->getConfig()->getValue(['parameters', 'type']));
    }

    protected function getConfigClass(): string
    {
        return Config::class;
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}
