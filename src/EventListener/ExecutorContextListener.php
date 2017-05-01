<?php

namespace Siad007\ZendExpressive\GraphQL\EventListener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class ExecutorContextListener implements ListenerAggregateInterface
{
    protected $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach('requestFiles', [$this, 'onExecutorContextEvent']);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            $events->detach($listener);
            unset($this->listeners[$index]);
        }
    }

    public function onExecutorContextEvent(ExecutorContextEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return;
        }
        $context = $event->getExecutorContext();
        $context['request_files'] = $request->files;
        $event->setExecutorContext($context);
    }
}
