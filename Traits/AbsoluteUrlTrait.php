<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Traits;

use Mindy\Application\App;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

trait AbsoluteUrlTrait
{
    /**
     * @see RouterInterface::generate()
     *
     * @return RouterInterface
     */
    private function getRouter()
    {
        return App::getInstance()->getComponent('router');
    }

    /**
     * @param $route
     * @param array $parameters
     * @param int   $referenceType
     *
     * @return string
     */
    public function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->getRouter()->generate($route, $parameters, $referenceType);
    }
}
