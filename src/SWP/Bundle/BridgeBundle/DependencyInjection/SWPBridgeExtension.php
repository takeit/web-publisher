<?php

/**
 * This file is part of the Superdesk Web Publisher Bridge Bundle.
 *
 * Copyright 2016 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2016 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */
namespace SWP\Bundle\BridgeBundle\DependencyInjection;

use SWP\Component\Storage\Drivers;
use SWP\Component\Storage\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SWPBridgeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $defaultOptions = array();
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('validators.yml');
        $loader->load('transformers.yml');

        $mainKeys = array('api', 'auth');
        foreach ($mainKeys as $mainKey) {
            if (isset($config[$mainKey])) {
                foreach ($config[$mainKey] as $key => $value) {
                    if (!empty($value)) {
                        $container->setParameter(sprintf('%s.%s.%s', $this->getAlias(), $mainKey, $key), $value);
                    }
                }
            }
        }

        if (isset($config['options']) && is_array($config['options'])) {
            $defaultOptions = $config['options'];
        }
        $container->setParameter($this->getAlias().'.options', $defaultOptions);
        if ($config['persistence']['orm']['enabled']) {
            $this->registerStorage(Drivers::DRIVER_DOCTRINE_ORM, $config['persistence']['orm'], $container);
            $container->setParameter(
                sprintf('%s.persistence.orm.manager_name', $this->getAlias()),
                $config['persistence']['orm']['object_manager_name']
            );

            $container->setParameter($this->getAlias().'.backend_type_orm', true);
        }
    }
}