<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Tests;

use Mindy\Bundle\MindyBundle\EventSubscriber\ExceptionEventSubscriber;
use Mindy\Template\Finder\StaticTemplateFinder;
use Mindy\Template\LoaderMode;
use Mindy\Template\TemplateEngine;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionSubscriberTest extends TestCase
{
    public function testExceptionEventSubscriber()
    {
        $templates = [
            '500.html' => '500.html',
            '403.html' => '403.html',
            '404.html' => '404.html',
        ];
        $templateEngine = new TemplateEngine(
            new StaticTemplateFinder($templates),
            __DIR__.'/var',
            LoaderMode::RECOMPILE_ALWAYS
        );
        $logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $kernel = $this
            ->getMockBuilder(KernelInterface::class)
            ->getMock();

        $subscriber = new ExceptionEventSubscriber($templateEngine, $logger, '%s.html', false);

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::SUB_REQUEST, new \Exception('test'));
        $subscriber->onKernelException($event);
        $this->assertNull($event->getResponse());

        $subscriber = new ExceptionEventSubscriber($templateEngine, $logger, '%s.html', true);

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::SUB_REQUEST, new \Exception('test'));
        $subscriber->onKernelException($event);
        $this->assertSame('500.html', $event->getResponse()->getContent());

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::SUB_REQUEST, new NotFoundHttpException());
        $subscriber->onKernelException($event);
        $this->assertSame('404.html', $event->getResponse()->getContent());

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::SUB_REQUEST, new AccessDeniedException());
        $subscriber->onKernelException($event);
        $this->assertSame('403.html', $event->getResponse()->getContent());
    }
}
