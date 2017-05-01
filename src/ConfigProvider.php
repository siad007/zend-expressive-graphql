<?php

namespace Siad007\ZendExpressive\GraphQL;

/**
 * Class GraphQL
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'zend-expressive-graphql' => [
                'endpoint' => 'default value',
            ]
        ];
    }

    public function getDependencies()
    {
        return [
            'invokables' => [],
            'factories' => [],
        ];
    }
}
