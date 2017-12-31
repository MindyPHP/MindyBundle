<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Tests;

use Mindy\Bundle\MindyBundle\EventSubscriber\ExceptionEventSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class KernelTest extends KernelTestCase
{
    protected function tearDown()
    {
        (new Filesystem())->remove(__DIR__.'/var');
    }

    protected static function createKernel(array $options = [])
    {
        return new Kernel('dev', true);
    }

    public function testExceptionEventSubscriber()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $this->assertInstanceOf(
            ExceptionEventSubscriber::class,
            $container->get(ExceptionEventSubscriber::class)
        );
    }
}
