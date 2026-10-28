<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Loader;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Loader\SharedThemeFilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Loader
 * @group SharedThemeFilesystemLoaderTest
 * Add your own group annotations below this line
 */
class SharedThemeFilesystemLoaderTest extends Unit
{
    protected const string ORGANIZATION_PROJECT = 'ProjectNamespace';

    protected const string ORGANIZATION_CORE = 'CoreNamespace';

    protected const string PATH_PATTERN_PROJECT = __DIR__ . '/../Fixtures/src/ProjectNamespace/Shared/%s/Theme/default';

    protected const string PATH_PATTERN_CORE = __DIR__ . '/../Fixtures/vendor/*/*/src/CoreNamespace/Shared/%s/Theme/default';

    protected const string CONTENT_PROJECT = 'project shared theme file' . PHP_EOL;

    protected const string CONTENT_CORE = 'core shared theme file' . PHP_EOL;

    public function testGivenCoreTemplateWhenLoadedThenTheCapitalizedDirectoryIsResolvedAsWritten(): void
    {
        // Arrange
        $sharedThemeFilesystemLoader = $this->createSharedThemeFilesystemLoader();

        // Act
        $source = $sharedThemeFilesystemLoader->getSourceContext('@Bundle/Chat/core.twig');

        // Assert
        $this->assertSame(static::CONTENT_CORE, $source->getCode());
    }

    public function testGivenProjectAndCoreTemplateWhenLoadedThenTheProjectTemplateWins(): void
    {
        // Arrange
        $sharedThemeFilesystemLoader = $this->createSharedThemeFilesystemLoader();

        // Act
        $source = $sharedThemeFilesystemLoader->getSourceContext('@Bundle/Chat/override.twig');

        // Assert
        $this->assertSame(static::CONTENT_PROJECT, $source->getCode());
    }

    public function testGivenOrganizationNamespaceWhenLoadedThenOnlyThatOrganizationIsLookedUp(): void
    {
        // Arrange
        $sharedThemeFilesystemLoader = $this->createSharedThemeFilesystemLoader();

        // Act
        $source = $sharedThemeFilesystemLoader->getSourceContext('@CoreNamespace:Bundle/Chat/override.twig');

        // Assert
        $this->assertSame(static::CONTENT_CORE, $source->getCode());
    }

    public function testGivenModuleWithoutSharedThemeWhenCheckedThenTheTemplateDoesNotExist(): void
    {
        // Arrange
        $sharedThemeFilesystemLoader = $this->createSharedThemeFilesystemLoader();

        // Act
        $exists = $sharedThemeFilesystemLoader->exists('@UnknownBundle/Chat/core.twig');

        // Assert
        $this->assertFalse($exists);
    }

    protected function createSharedThemeFilesystemLoader(): SharedThemeFilesystemLoader
    {
        return new SharedThemeFilesystemLoader([
            static::ORGANIZATION_PROJECT => [static::PATH_PATTERN_PROJECT],
            static::ORGANIZATION_CORE => [static::PATH_PATTERN_CORE],
        ]);
    }
}
