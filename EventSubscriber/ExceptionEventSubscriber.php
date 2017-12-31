<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\EventSubscriber;

use Mindy\Template\TemplateEngine;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Throwable;

class ExceptionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var TemplateEngine
     */
    protected $template;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $enable = false;

    /**
     * ExceptionListener constructor.
     *
     * @param TemplateEngine $template
     * @param LoggerInterface $logger
     * @param string $path
     * @param bool $enable
     */
    public function __construct(
        TemplateEngine $template,
        LoggerInterface $logger,
        $path = 'mindy/error/%s.html',
        $enable = false
    ) {
        $this->template = $template;
        $this->logger = $logger;
        $this->path = $path;
        $this->enable = $enable;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (false === $this->enable) {
            return;
        }

        // You get the exception object from the received event
        $exception = $event->getException();

        // Log exception
        $this->logException($exception);

        // Customize your response object to display the exception details
        $response = new Response();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } elseif ($exception instanceof AccessDeniedException || $exception instanceof InvalidCsrfTokenException) {
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setContent($this->renderException($exception));
        $event->setResponse($response);
    }

    /**
     * @param Throwable $exception
     *
     * @return string
     */
    protected function renderException(Throwable $exception)
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        } elseif ($exception instanceof AccessDeniedException || $exception instanceof InvalidCsrfTokenException) {
            $code = Response::HTTP_FORBIDDEN;
        }

        return $this->template->render(sprintf($this->path, $code), [
            'exception' => $exception,
        ]);
    }

    /**
     * @param Throwable $exception
     */
    protected function logException(Throwable $exception)
    {
        $this->logger->error($exception->getMessage(), [
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
