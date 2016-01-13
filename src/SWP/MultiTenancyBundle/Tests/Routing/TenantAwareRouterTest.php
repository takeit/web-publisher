<?php
/**
 * This file is part of the Superdesk Web Publisher Web Renderer Bundle.
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */
namespace SWP\WebRendererBundle\Tests\Routing;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class TenantAwareRouterTest extends WebTestCase
{
    public function testGenerate()
    {
        self::bootKernel();
        $this->runCommand('doctrine:phpcr:init:dbal', ['--force' => true, '--env' => 'test'], true);
        $this->runCommand('doctrine:phpcr:repository:init', ['--env' => 'test'], true);
        $this->loadFixtures([
            'SWP\FixturesBundle\DataFixtures\ORM\LoadTenantsData',
        ]);

        $router = $this->getContainer()->get('cmf_routing.dynamic_router');
        $this->assertEquals('/news/test-news-article', $router->generate('/swp/content/test-news-article'));
    }
}
