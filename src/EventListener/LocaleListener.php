<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class LocaleListener
{
    public function __construct(private RequestStack $requestStack)
    {
    }
    #[AsEventListener]
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $locale = $request->attributes->get('_locale') ?? $request->getSession()->get('_locale', 'fr');
        $request->setLocale($locale);
    }
}
