<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Tests;

use PHPUnit\Framework\TestCase;

class PaginationTraitControllerTest extends TestCase
{
    public function testCreatePagination()
    {
        $c = new ExampleController();
        $this->assertTrue(method_exists($c, 'createPagination'));
    }
}
