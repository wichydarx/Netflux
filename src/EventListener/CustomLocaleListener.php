<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CustomLocaleListener
{

    #[AsEventListener(priority: 101)]
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        if ($locale = $request->query->get('_locale')) {
            $request->setLocale($locale);
        }else{
            $request->setLocale($request->getSession()->get('_locale', $request->getDefaultLocale()));
        }
        
    }
}
