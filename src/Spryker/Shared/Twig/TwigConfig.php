<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Kernel\KernelConstants;
use Twig\Cache\FilesystemCache;

class TwigConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @api
     *
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return $this->get(TwigConstants::YVES_THEME, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProjectNamespaces(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * Specification:
     * - Returns the directory patterns of the Shared theme templates (`Shared/<Module>/Theme/<theme>`), keyed by organization, in lookup order.
     * - `%s` in a pattern stands for the module name.
     * - Project namespaces come first, each with the given theme names in the given order.
     * - Core namespaces follow with the default theme only.
     *
     * @api
     *
     * @param list<string> $themeNames
     *
     * @return array<string, list<string>>
     */
    public function getSharedThemeTemplatePathPatterns(array $themeNames): array
    {
        $templatePathPatternsByOrganization = [];

        foreach ($this->getProjectNamespaces() as $namespace) {
            foreach ($themeNames as $themeName) {
                $templatePathPatternsByOrganization[$namespace][] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Shared/%s/Theme/' . $themeName;
            }
        }

        foreach ($this->getCoreNamespaces() as $namespace) {
            $templatePathPatternsByOrganization[$namespace][] = rtrim(APPLICATION_VENDOR_DIR, DIRECTORY_SEPARATOR) . '/*/*/src/' . $namespace . '/Shared/%s/Theme/' . static::THEME_NAME_DEFAULT;
        }

        return $templatePathPatternsByOrganization;
    }

    /**
     * Specification:
     * - Defines the default path to the Twig `.pathCache` file.
     * - Can be redefined on Yves or Zed configs.
     *
     * @api
     *
     * @param string $application
     *
     * @return string
     */
    public function getDefaultPathCache($application = APPLICATION): string
    {
        return sprintf(
            '%s/src/Generated/%s/Twig/codeBucket/.pathCache',
            APPLICATION_ROOT_DIR,
            ucfirst(strtolower($application)),
        );
    }

    /**
     * Specification:
     * - Defines the default twig options.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getDefaultTwigOptions(): array
    {
        return [
            'cache' => new FilesystemCache(
                sprintf(
                    '%s/src/Generated/%s/Twig/codeBucket',
                    APPLICATION_ROOT_DIR,
                    ucfirst(strtolower(APPLICATION)),
                ),
                FilesystemCache::FORCE_BYTECODE_INVALIDATION,
            ),
        ];
    }
}
