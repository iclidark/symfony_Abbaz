<?php

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\ControllerLoggerSubscriber;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ControllerLoggerSubscriberTest extends TestCase
{
    private $kernel;

    protected function setUp(): void
    {
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    private function createEventForController(string $controllerRef): ControllerEvent
    {
        $request = new Request();
        $request->attributes->set('_controller', $controllerRef);

        // The callable can be a simple closure as it's not directly inspected by our subscriber anymore.
        $callable = function() {}; 

        return new ControllerEvent($this->kernel, $callable, $request, HttpKernelInterface::MAIN_REQUEST);
    }

    public function testLogTaskListAccessedForAuthenticatedUser(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $security = $this->createMock(Security::class);
        $user = $this->createMock(User::class);

        $user->method('getUserIdentifier')->willReturn('testuser');
        $security->method('getUser')->willReturn($user);

        $logger->expects($this->once())
            ->method('info')
            ->with('Task list page accessed by testuser');

        $subscriber = new ControllerLoggerSubscriber($logger, $security);

        $event = $this->createEventForController('App\\Controller\\TaskController::list');
        $subscriber->onKernelController($event);
    }

    public function testLogTaskListAccessedForAnonymousUser(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $security = $this->createMock(Security::class);

        $security->method('getUser')->willReturn(null);

        $logger->expects($this->once())
            ->method('info')
            ->with('Task list page accessed by anonymous');

        $subscriber = new ControllerLoggerSubscriber($logger, $security);

        $event = $this->createEventForController('App\\Controller\\TaskController::list');
        $subscriber->onKernelController($event);
    }

    public function testDoesNotLogForOtherControllers(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $security = $this->createMock(Security::class);

        $logger->expects($this->never())->method('info');

        $subscriber = new ControllerLoggerSubscriber($logger, $security);

        $event = $this->createEventForController('App\\Controller\\SomeOtherController::someAction');
        $subscriber->onKernelController($event);
    }
}
