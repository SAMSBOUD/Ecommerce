<?php
// src/EventListener/LocaleListener.php
// src/EventListener/LocaleListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleListener
{
    private $defaultLocale;
    private $requestStack;

    public function __construct($defaultLocale = 'fr')
    {
        //$this->requestStack = $request;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // Try to set the locale from the session
        $session = $request->getSession();
        dd($session);
        if ($locale = $session->get('_locale')) {
            $request->setLocale($locale);
        } else {
            $request->setLocale($this->defaultLocale);
        }
    }
}
