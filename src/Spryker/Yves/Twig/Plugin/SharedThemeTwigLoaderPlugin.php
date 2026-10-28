<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin;

use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
 * @method \Spryker\Yves\Twig\TwigConfig getConfig()
 */
class SharedThemeTwigLoaderPlugin extends AbstractPlugin implements TwigLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Resolves `@Module/<path>` from the `Shared/Module/Theme/<theme>` directories of all project and core namespaces.
     * - Resolves `@Organization:Module/<path>` from the directories of that organization only, so a project template can extend the core one it replaces.
     * - Looks up project namespaces first; within them the configured Yves theme before the default theme.
     * - Keeps the template path as written, so `@Module/Chat/...` resolves to `Theme/<theme>/Chat/...`.
     * - Register after `FilesystemTwigLoaderPlugin`: a template that also exists in a Yves theme is served from there.
     *
     * @api
     *
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function getLoader(): FilesystemLoaderInterface
    {
        return $this->getFactory()->createSharedThemeFilesystemLoader();
    }
}
