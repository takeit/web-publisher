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
namespace spec\SWP\Bundle\WebRendererBundle\Theme;

use PhpSpec\ObjectBehavior;
use SWP\Component\MultiTenancy\Context\TenantContextInterface;
use SWP\Component\MultiTenancy\Model\TenantInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;

class TenantAwareThemeContextSpec extends ObjectBehavior
{
    function let(TenantContextInterface $tenantContext, ThemeRepositoryInterface $themeRepository)
    {
        $this->beConstructedWith($tenantContext, $themeRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Bundle\WebRendererBundle\Theme\TenantAwareThemeContext');
    }

    function it_returns_a_theme(
        TenantContextInterface $tenantContext,
        TenantInterface $tenant,
        ThemeInterface $theme,
        ThemeRepositoryInterface $themeRepository
    ) {
        $tenantContext->getTenant()->willReturn($tenant);
        $tenant->getThemeName()->willReturn('swp/default-theme');
        $themeRepository->findOneByName('swp/default-theme')->willReturn($theme);

        $this->getTheme()->shouldReturn($theme);
    }

    function it_returns_null_if_tenant_has_no_theme(
        TenantContextInterface $tenantContext,
        TenantInterface $tenant
    ) {
        $tenantContext->getTenant()->willReturn($tenant);
        $tenant->getThemeName()->willReturn(null);
        $this->getTheme()->shouldReturn(null);
    }
}
