<?php

namespace Siad007\ZendExpressive\GraphQL\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Siad007\ZendExpressive\GraphQL\Request\Parser;
use Zend\Diactoros\Response;

class GraphController extends AbstractController
{
    public function endpointAction(ServerRequestInterface $request, $schemaName = null)
    {
        $payload = $this->processNormalQuery($request, $schemaName);

        return new Response\JsonResponse($payload, 200);
    }

    public function batchAction(ServerRequestInterface $request, $schemaName = null)
    {
        $payloads = $this->processBatchQuery($request, $schemaName);

        return new Response\JsonResponse($payloads, 200);
    }

    private function processBatchQuery(ServerRequestInterface $request, $schemaName = null)
    {
        $queries = $this->get(BatchParser::class)->parse($request);
        $payloads = [];
        foreach ($queries as $query) {
            $payloadResult = $this->get(Executor::class)->execute(
                [
                    'query' => $query['query'],
                    'variables' => $query['variables'],
                ],
                [],
                $schemaName
            );
            $payloads[] = ['id' => $query['id'], 'payload' => $payloadResult->toArray()];
        }
        return $payloads;
    }

    private function processNormalQuery(ServerRequestInterface $request, $schemaName = null)
    {
        $params = $this->get(Parser::class)->parse($request);

        return $this->get(Executor::class)->execute($params, [], $schemaName)->toArray();
    }
}
