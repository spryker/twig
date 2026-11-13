<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin;
use Spryker\Yves\Twig\TwigDependencyProvider;
use SprykerTest\Yves\Twig\TwigYvesTester;
use Twig\Environment;
use Twig\Error\SyntaxError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group Plugin
 * @group Application
 * @group TwigApplicationPluginTest
 * Add your own group annotations below this line
 */
class TwigApplicationPluginTest extends Unit
{
    protected const string TEMPLATE_WITH_UNDEFINED_FUNCTION_FALLBACK_ENABLED = '{{ undefinedYvesFunctionWithFallback("input") }}';

    protected const string TEMPLATE_WITH_UNDEFINED_FUNCTION_FALLBACK_DISABLED = '{{ undefinedYvesFunctionWithoutFallback("input") }}';

    protected TwigYvesTester $tester;

    public function testGivenUndefinedCallableFallbackEnabledWhenTemplateCallsUndefinedFunctionThenInputIsRendered(): void
    {
        // Arrange
        $twig = $this->createTwigEnvironment(true);

        // Act
        $output = $twig->createTemplate(static::TEMPLATE_WITH_UNDEFINED_FUNCTION_FALLBACK_ENABLED)->render();

        // Assert
        $this->assertSame('input', $output);
    }

    /**
     * Twig skips compiling a template whose class already exists in the process, so each test needs its own template source.
     */
    public function testGivenUndefinedCallableFallbackDisabledWhenTemplateCallsUndefinedFunctionThenSyntaxErrorIsThrown(): void
    {
        // Arrange
        $twig = $this->createTwigEnvironment(false);

        // Expect
        $this->expectException(SyntaxError::class);

        // Act
        $twig->createTemplate(static::TEMPLATE_WITH_UNDEFINED_FUNCTION_FALLBACK_DISABLED);
    }

    protected function createTwigEnvironment(bool $isUndefinedCallableFallbackEnabled): Environment
    {
        $this->tester->mockSharedConfigMethod('isUndefinedCallableFallbackEnabled', $isUndefinedCallableFallbackEnabled);
        $this->tester->setConfig(TwigConstants::YVES_TWIG_OPTIONS, ['cache' => false]);
        $this->tester->setDependency(TwigDependencyProvider::PLUGINS_TWIG, []);
        $this->tester->setDependency(TwigDependencyProvider::PLUGINS_TWIG_LOADER, []);

        $container = new Container([
            TwigApplicationPlugin::SERVICE_DEBUG => false,
            TwigApplicationPlugin::SERVICE_CHARSET => 'UTF-8',
        ]);
        $twigApplicationPlugin = new TwigApplicationPlugin();
        $twigApplicationPlugin->setFactory($this->tester->getFactory());
        $twigApplicationPlugin->provide($container);

        return $container->get(TwigApplicationPlugin::SERVICE_TWIG);
    }
}
