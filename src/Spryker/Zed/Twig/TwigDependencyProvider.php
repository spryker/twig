<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 */
class TwigDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'util text service';

    /**
     * @var string
     */
    public const PLUGINS_TWIG = 'PLUGINS_TWIG';

    /**
     * @var string
     */
    public const PLUGINS_TWIG_LOADER = 'PLUGINS_TWIG_LOADER';

    /**
     * @var string
     */
    public const PLUGINS_TWIG_GATEWAY = 'PLUGINS_TWIG_GATEWAY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addUtilTextService($container);
        $container = $this->addTwigPlugins($container);
        $container = $this->addTwigLoaderPlugins($container);
        $container = $this->addTwigGatewayPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new TwigToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_TWIG, function (Container $container) {
            return $this->getTwigPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface>
     */
    protected function getTwigPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigGatewayPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_TWIG_GATEWAY, function (Container $container) {
            return $this->getTwigGatewayPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface>
     */
    protected function getTwigGatewayPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigLoaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_TWIG_LOADER, function (Container $container) {
            return $this->getTwigLoaderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface>
     */
    protected function getTwigLoaderPlugins(): array
    {
        return [];
    }
}
