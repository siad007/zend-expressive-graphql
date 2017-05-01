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
            'zend-expressive-graphql' => [
                'some-setting' => 'default value',
            ]
        ];
    }
}
