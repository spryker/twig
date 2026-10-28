<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin;

use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class SharedThemeTwigLoaderPlugin extends AbstractPlugin implements TwigLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Resolves `@Module/<path>` from the `Shared/Module/Theme/default` directories of all project and core namespaces.
     * - Resolves `@Organization:Module/<path>` from the directories of that organization only, so a project template can extend the core one it replaces.
     * - Looks up project namespaces first.
     * - Keeps the template path as written, so `@Module/Chat/...` resolves to `Theme/default/Chat/...`.
     * - Register after `FilesystemTwigLoaderPlugin`: a template that also exists in a `Presentation` directory is served from there.
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
