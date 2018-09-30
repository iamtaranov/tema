<?php

namespace ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

        switch ($event->getResponse()->headers->get('content-type'))
        {
            case 'application/json':

                $response = $event->getResponse();

                $response->setContent(json_encode(json_decode($response->getContent()), JSON_UNESCAPED_UNICODE));

                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

                break;
        }

        // only do something when the requested format is "json"
//        if ($request->getRequestFormat() != 'json') {
//            return;
//        }
//
//        // only do something when the client accepts "text/html" as response format
//        if (false === strpos($request->headers->get('Accept'), 'text/html')) {
//            return;
//        }
//
//        // set the "Content-Type" header of the response
//        $event->getResponse()->headers->set('Content-Type', 'text/plain');
    }
}