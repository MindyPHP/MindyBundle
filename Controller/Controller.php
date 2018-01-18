<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Controller;

use Mindy\Bundle\PaginationBundle\Utils\PaginationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

if (false === trait_exists(PaginationTrait::class)) {
    trait PaginationTraitMock
    {
    }

    class_alias(PaginationTraitMock::class, PaginationTrait::class);
}

/**
 * Class Controller
 */
abstract class Controller extends BaseController
{
    use PaginationTrait;

    /**
     * {@inheritdoc}
     * {@see Controller::renderView}
     */
    public function renderTemplate($view, array $parameters = array())
    {
        return parent::renderView($view, $parameters);
    }
}
