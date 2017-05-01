<?php

namespace Siad007\ZendExpressive\GraphQL\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

abstract class AbstractController implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $action = $request->getAttribute('action', 'index') . 'Action';

        if (method_exists($this, $action)) {
            $r = new \ReflectionMethod($this, $action);
            $args = $r->getParameters();

            if (count($args) === 2
                && $args[0]->getType() == ServerRequestInterface::class
                && $args[1]->getType() == ResponseInterface::class
            ) {
                return $this->$action($request, $delegate);
            }
        }

        return $delegate->process($request);
    }
}
