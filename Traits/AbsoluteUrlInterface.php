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

/**
 * Interface AbsoluteUrlInterface
 */
interface AbsoluteUrlInterface
{
    /**
     * @return string
     */
    public function getAbsoluteUrl();
}
