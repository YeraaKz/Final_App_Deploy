<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

final class AccessDeniedListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedHttpException) {
            $session = $event->getRequest()->getSession();
            $session->getBag('flashes')->add('error' , 'Access denied');
            $response = new RedirectResponse($this->router->generate('app_main'));
            $event->setResponse($response);
        }
    }

}
