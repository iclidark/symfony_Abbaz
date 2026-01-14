<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerLoggerSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private Security $security;

    public function __construct(LoggerInterface $logger, Security $security)
    {
        $this->logger = $logger;
        $this->security = $security;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Rely on the _controller request attribute, which is a reliable string representation.
        $controllerRef = $event->getRequest()->attributes->get('_controller');

        if ($controllerRef === 'App\\Controller\\TaskController::list') {
            $user = $this->security->getUser();
            $username = $user ? $user->getUserIdentifier() : 'anonymous';

            $this->logger->info('Task list page accessed by ' . $username);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
