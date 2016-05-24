<?php

/**
 * This file is part of the Superdesk Web Publisher Web Renderer Bundle.
 *
 * Copyright 2016 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2016 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */
namespace spec\SWP\Bundle\WebRendererBundle\Theme\Factory;

use PhpSpec\ObjectBehavior;
use SWP\Bundle\WebRendererBundle\Theme\Factory\ThemeFactory;
use Sylius\Bundle\ThemeBundle\Factory\ThemeFactoryInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

/**
 * @mixin ThemeFactory
 */
class ThemeFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ThemeFactory::class);
    }

    function it_implements_theme_factory_interface()
    {
        $this->shouldImplement(ThemeFactoryInterface::class);
    }

    function it_creates_a_theme()
    {
        $this->create('example/theme@subdomain', '/theme/path')->shouldHaveNameAndPath('example/theme', '/theme/path');
        $this->create('example/theme', '/theme/path')->shouldHaveNameAndPath('example/theme', '/theme/path');
    }

    function it_cant_create_a_theme()
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('create', ['testtheme', '/theme/path']);
    }

    public function getMatchers()
    {
        return [
            'haveNameAndPath' => function (ThemeInterface $theme, $expectedName, $expectedPath) {
                return $expectedName === $theme->getName()
                && $expectedPath === $theme->getPath()
                    ;
            },
        ];
    }
}
