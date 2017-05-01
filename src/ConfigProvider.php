<?php

namespace Siad007\ZendExpressive\GraphQL;

use Siad007\ZendExpressive\GraphQL\Request\BatchParser;
use Siad007\ZendExpressive\GraphQL\Request\Parser;

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
            'services' => [
                Parser::class => new Parser(),
                BatchParser::class => new BatchParser(),
                Executor::class => new Executor(),
            ],
            'invokables' => [],
            'factories' => [],
        ];
    }
}
