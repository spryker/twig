<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Loader;

class SharedThemeFilesystemLoader extends FilesystemLoader
{
    protected const string NAMESPACE_PREFIX = '@';

    protected const string NAMESPACE_SEPARATOR = '/';

    /**
     * @var non-empty-string
     */
    protected const string ORGANIZATION_SEPARATOR = ':';

    /**
     * @var array<string, true>
     */
    protected array $resolvedNamespaces = [];

    /**
     * @param array<string, list<string>> $templatePathPatternsByOrganization
     */
    public function __construct(protected array $templatePathPatternsByOrganization)
    {
        parent::__construct();
    }

    /**
     * @param string $name
     * @param bool $throw
     */
    protected function findTemplate($name, $throw = true): ?string
    {
        $this->addNamespaceTemplatePaths((string)$name);

        return parent::findTemplate($name, $throw);
    }

    protected function addNamespaceTemplatePaths(string $name): void
    {
        $namespace = $this->findNamespace($name);

        if ($namespace === null || isset($this->resolvedNamespaces[$namespace])) {
            return;
        }

        $this->resolvedNamespaces[$namespace] = true;
        $moduleName = $namespace;
        $templatePathPatternsByOrganization = $this->templatePathPatternsByOrganization;

        if (str_contains($namespace, static::ORGANIZATION_SEPARATOR)) {
            [$organization, $moduleName] = explode(static::ORGANIZATION_SEPARATOR, $namespace, 2);
            $templatePathPatternsByOrganization = [$organization => $this->templatePathPatternsByOrganization[$organization] ?? []];
        }

        foreach ($templatePathPatternsByOrganization as $templatePathPatterns) {
            $this->addTemplatePaths($templatePathPatterns, $moduleName, $namespace);
        }
    }

    /**
     * @param list<string> $templatePathPatterns
     */
    protected function addTemplatePaths(array $templatePathPatterns, string $moduleName, string $namespace): void
    {
        foreach ($templatePathPatterns as $templatePathPattern) {
            foreach (glob(sprintf($templatePathPattern, $moduleName), GLOB_ONLYDIR | GLOB_NOSORT) ?: [] as $templatePath) {
                $this->addPath($templatePath, $namespace);
            }
        }
    }

    protected function findNamespace(string $name): ?string
    {
        if (!str_starts_with($name, static::NAMESPACE_PREFIX)) {
            return null;
        }

        $separatorPosition = strpos($name, static::NAMESPACE_SEPARATOR);

        if ($separatorPosition === false) {
            return null;
        }

        return substr($name, 1, $separatorPosition - 1);
    }
}
