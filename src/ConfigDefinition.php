<?php

declare(strict_types=1);

namespace StampFilenamesProcessor;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ConfigDefinition extends BaseConfigDefinition
{
    protected function getParametersDefinition(): ArrayNodeDefinition
    {
        $parametersNode = parent::getParametersDefinition();
        // @formatter:off
        /** @noinspection NullPointerExceptionInspection */
        $parametersNode
            ->children()
                ->enumNode('type')
                    ->values(array('file', 'table'))
                ->end()
                ->enumNode('placement')
                    ->values(array('prepend', 'append'))
                ->end()
                ->scalarNode('format')
                    ->defaultValue('Y-m-d_His')
                ->end()
            ->end()
        ;
        // @formatter:on
        return $parametersNode;
    }
}
