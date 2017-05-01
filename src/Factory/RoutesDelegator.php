<?php

namespace Siad007\ZendExpressive\GraphQL\Factory;

use Interop\Container\ContainerInterface;
use Siad007\ZendExpressive\GraphQL\Middleware\GraphMiddleware;
use Zend\Expressive\Application;

class RoutesDelegator
{
    /**
     * @param ContainerInterface $container
     * @param string $serviceName Name of the service being created.
     * @param callable $callback Creates and returns the service.
     * @return Application
     */
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback)
    {
        /** @var $app Application */
        $app = $callback();

        $app->get('/', GraphMiddleware::class, 'endpoint');
        $app->get('/batch', BatchMiddleware::class, 'batch_endpoint');
        $app->get('/graphql/:schemaName', MultipleGraphMiddleware::class, 'graphql_schema_endpoint');
        $app->get('/graphql/:schemaName/batch', MultipleBatchMiddleware::class, 'graphql_schema_batch_endpoint');

        return $app;
    }
}
