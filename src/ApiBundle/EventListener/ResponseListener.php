<?php

namespace ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        switch ($event->getResponse()->headers->get('content-type'))
        {
            case 'application/json':

                $response = $event->getResponse();

                $response->setContent(json_encode(json_decode($response->getContent()), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

                break;
        }
    }
}