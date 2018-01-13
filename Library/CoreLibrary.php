<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MindyBundle\Library;

use Mindy\Template\Library\AbstractLibrary;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

class CoreLibrary extends AbstractLibrary
{
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var Translator
     */
    protected $translator;
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * CoreLibrary constructor.
     *
     * @param RouterInterface               $router
     * @param Translator                    $translator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorage                  $tokenStorage
     */
    public function __construct(
        RouterInterface $router,
        Translator $translator,
        AuthorizationCheckerInterface $authorizationChecker = null,
        TokenStorage $tokenStorage = null
    ) {
        $this->router = $router;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'switched_user' => function () {
                if ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
                    foreach ($this->tokenStorage->getToken()->getRoles() as $role) {
                        if ($role instanceof SwitchUserRole) {
                            return $role->getSource()->getUser();
                        }
                    }
                }

                return null;
            },
            'is_granted' => [$this->authorizationChecker, 'isGranted'],
            'path' => [$this->router, 'generate'],
            'url' => [$this->router, 'generate'],
            'd' => [$this, 'dump'],
            't' => [$this->translator, 'trans'],
            'trans' => [$this->translator, 'trans'],
            'transChoice' => [$this->translator, 'transChoice'],
        ];
    }

    public function dump(...$arguments)
    {
        if (function_exists('dump')) {
            dump($arguments);
        } else {
            var_dump($arguments);
        }
    }
}
