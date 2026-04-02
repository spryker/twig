<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Twig\Extender\FilterExtenderInterface;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin;
use Spryker\Zed\Twig\Communication\TwigCommunicationFactory;
use Spryker\Zed\Twig\TwigConfig;
use SprykerTest\Zed\Twig\TwigCommunicationTester;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\TwigFunction;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Communication
 * @group Plugin
 * @group Application
 * @group TwigApplicationPluginTest
 * Add your own group annotations below this line
 */
class TwigApplicationPluginTest extends Unit
{
    protected TwigCommunicationTester $tester;

    protected const string TWIG_FUNCTION_NAME = 'configurationValue';

    protected const string TWIG_FUNCTION_NAME_VALUES = 'configurationValues';

    protected const string TEST_CONFIG_KEY = 'theme:storefront:colors:main_color';

    protected const string TEST_CONFIG_VALUE = '#00bebe';

    protected const string TEST_CONFIG_PREFIX = 'theme:storefront:colors';

    /**
     * @var array<string, string>
     */
    protected const array TEST_CONFIG_VALUES = ['yves_main_color' => '#ffffff'];

    public function testProvideShouldRegisterTwigServiceInContainer(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $containerMock = $this->createMock(ContainerInterface::class);

        // Expect
        $containerMock->expects($this->once())
            ->method('set')
            ->with(TwigApplicationPlugin::SERVICE_TWIG, $this->isCallable());

        // Act
        $plugin->provide($containerMock);
    }

    public function testTwigServiceShouldReturnTwigEnvironmentInstance(): void
    {
        // Act
        $twig = $this->invokeTwigServiceClosure();

        // Assert
        $this->assertInstanceOf(Environment::class, $twig);
    }

    public function testTwigServiceShouldAddAppGlobalToTwigEnvironment(): void
    {
        // Arrange
        [$twig, $container] = $this->invokeTwigServiceClosureWithContainer();

        // Assert
        $this->assertSame($container, $twig->getGlobals()['app']);
    }

    public function testTwigServiceShouldRegisterConfigurationValueFunction(): void
    {
        // Act
        $twig = $this->invokeTwigServiceClosure();

        // Assert
        $this->assertNotFalse($twig->getFunction(static::TWIG_FUNCTION_NAME));
    }

    public function testTwigServiceShouldRegisterConfigurationValuesFunction(): void
    {
        // Act
        $twig = $this->invokeTwigServiceClosure();

        // Assert
        $this->assertNotFalse($twig->getFunction(static::TWIG_FUNCTION_NAME_VALUES));
    }

    public function testTwigServiceShouldCallTwigPluginExtend(): void
    {
        // Arrange
        $twigPluginMock = $this->createMock(TwigPluginInterface::class);
        $twigPluginMock->expects($this->once())
            ->method('extend')
            ->willReturnArgument(0);

        // Act
        $this->invokeTwigServiceClosure([$twigPluginMock]);
    }

    public function testGetTwigOptionsShouldIncludeCharsetFromContainer(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $containerMock = $this->createContainerMock('iso-8859-1');

        // Act
        $options = $plugin->testGetTwigOptions($containerMock);

        // Assert
        $this->assertSame('iso-8859-1', $options['charset']);
    }

    public function testGetTwigOptionsShouldEnableDebugWhenDebugServiceIsTrue(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $containerMock = $this->createContainerMock('UTF-8', true);

        // Act
        $options = $plugin->testGetTwigOptions($containerMock);

        // Assert
        $this->assertTrue($options['debug']);
        $this->assertTrue($options['strict_variables']);
    }

    public function testGetTwigOptionsShouldDisableDebugWhenDebugServiceIsNotAvailable(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $containerMock = $this->createContainerMock('UTF-8');

        // Act
        $options = $plugin->testGetTwigOptions($containerMock);

        // Assert
        $this->assertFalse($options['debug']);
        $this->assertFalse($options['strict_variables']);
    }

    public function testGetTwigOptionsShouldMergeConfigOptionsOverGlobalOptions(): void
    {
        // Arrange
        $plugin = $this->createTestDouble(configTwigOptions: ['cache' => '/tmp/cache']);
        $containerMock = $this->createContainerMock('UTF-8');

        // Act
        $options = $plugin->testGetTwigOptions($containerMock);

        // Assert
        $this->assertSame('/tmp/cache', $options['cache']);
    }

    public function testGetChainLoaderShouldReturnChainLoader(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();

        // Act
        $chainLoader = $plugin->testGetChainLoader();

        // Assert
        $this->assertInstanceOf(ChainLoader::class, $chainLoader);
    }

    public function testGetChainLoaderShouldCallGetLoaderOnAllRegisteredLoaderPlugins(): void
    {
        // Arrange
        $loaderMock = $this->createMock(FilesystemLoaderInterface::class);

        $loaderPluginMock = $this->createMock(TwigLoaderPluginInterface::class);
        $loaderPluginMock->expects($this->once())
            ->method('getLoader')
            ->willReturn($loaderMock);

        $plugin = $this->createTestDouble(loaderPlugins: [$loaderPluginMock]);

        // Act
        $plugin->testGetChainLoader();
    }

    public function testExtendTwigShouldCallExtendOnAllRegisteredPlugins(): void
    {
        // Arrange
        $twigMock = $this->createMock(Environment::class);
        $containerMock = $this->createMock(ContainerInterface::class);

        $firstPluginMock = $this->createMock(TwigPluginInterface::class);
        $firstPluginMock->expects($this->once())->method('extend')->willReturn($twigMock);

        $secondPluginMock = $this->createMock(TwigPluginInterface::class);
        $secondPluginMock->expects($this->once())->method('extend')->willReturn($twigMock);

        $plugin = $this->createTestDouble(twigPlugins: [$firstPluginMock, $secondPluginMock]);

        // Act
        $plugin->testExtendTwig($twigMock, $containerMock);
    }

    public function testExtendTwigShouldReturnTwigEnvironment(): void
    {
        // Arrange
        $twigMock = $this->createMock(Environment::class);
        $plugin = $this->createTestDouble();

        // Act
        $result = $plugin->testExtendTwig($twigMock, $this->createMock(ContainerInterface::class));

        // Assert
        $this->assertSame($twigMock, $result);
    }

    public function testAddGlobalTwigFiltersShouldDelegateToFilterExtender(): void
    {
        // Arrange
        $twigMock = $this->createMock(Environment::class);

        $filterExtenderMock = $this->createMock(FilterExtenderInterface::class);
        $filterExtenderMock->expects($this->once())
            ->method('extend')
            ->with($twigMock)
            ->willReturn($twigMock);

        $plugin = $this->createTestDouble(filterExtender: $filterExtenderMock);

        // Act
        $result = $plugin->testAddGlobalTwigFilters($twigMock);

        // Assert
        $this->assertSame($twigMock, $result);
    }

    public function testAddConfigurationValueTwigFunctionShouldRegisterFunctionInTwig(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $twigMock = $this->createMock(Environment::class);

        // Expect
        $twigMock->expects($this->once())
            ->method('addFunction')
            ->with($this->callback(
                static fn (TwigFunction $fn): bool => $fn->getName() === static::TWIG_FUNCTION_NAME,
            ));

        // Act
        $plugin->testAddConfigurationValueTwigFunction($twigMock);
    }

    public function testAddConfigurationValueTwigFunctionShouldReturnTwigEnvironment(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $twigMock = $this->createMock(Environment::class);

        // Act
        $result = $plugin->testAddConfigurationValueTwigFunction($twigMock);

        // Assert
        $this->assertSame($twigMock, $result);
    }

    public function testCreateConfigurationValueTwigFunctionShouldProvideCallable(): void
    {
        // Arrange
        $configMock = $this->createMock(TwigConfig::class);
        $configMock->method('getModuleConfig')
            ->with(static::TEST_CONFIG_KEY, null)
            ->willReturn(static::TEST_CONFIG_VALUE);

        $factory = new TwigCommunicationFactory();
        $factory->setConfig($configMock);

        // Act
        $function = $factory->createConfigurationValueTwigFunction();
        $result = call_user_func($function->getCallable(), static::TEST_CONFIG_KEY);

        // Assert
        $this->assertInstanceOf(TwigFunction::class, $function);
        $this->assertSame(static::TWIG_FUNCTION_NAME, $function->getName());
        $this->assertSame(static::TEST_CONFIG_VALUE, $result);
    }

    public function testAddConfigurationValuesTwigFunctionShouldRegisterFunctionInTwig(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $twigMock = $this->createMock(Environment::class);

        // Expect
        $twigMock->expects($this->once())
            ->method('addFunction')
            ->with($this->callback(
                static fn (TwigFunction $fn): bool => $fn->getName() === static::TWIG_FUNCTION_NAME_VALUES,
            ));

        // Act
        $plugin->testAddConfigurationValuesTwigFunction($twigMock);
    }

    public function testAddConfigurationValuesTwigFunctionShouldReturnTwigEnvironment(): void
    {
        // Arrange
        $plugin = $this->createTestDouble();
        $twigMock = $this->createMock(Environment::class);

        // Act
        $result = $plugin->testAddConfigurationValuesTwigFunction($twigMock);

        // Assert
        $this->assertSame($twigMock, $result);
    }

    public function testCreateConfigurationValuesTwigFunctionShouldProvideCallable(): void
    {
        // Arrange
        $configMock = $this->createMock(TwigConfig::class);
        $configMock->method('getModuleConfigValues')
            ->with(static::TEST_CONFIG_PREFIX)
            ->willReturn(static::TEST_CONFIG_VALUES);

        $factory = new TwigCommunicationFactory();
        $factory->setConfig($configMock);

        // Act
        $function = $factory->createConfigurationValuesTwigFunction();
        $result = call_user_func($function->getCallable(), static::TEST_CONFIG_PREFIX);

        // Assert
        $this->assertInstanceOf(TwigFunction::class, $function);
        $this->assertSame(static::TWIG_FUNCTION_NAME_VALUES, $function->getName());
        $this->assertSame(static::TEST_CONFIG_VALUES, $result);
    }

    protected function createPlugin(): TwigApplicationPlugin
    {
        $plugin = new TwigApplicationPlugin();
        $plugin->setFactory($this->createFactoryMock());

        return $plugin;
    }

    /**
     * @param array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface> $loaderPlugins
     * @param array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface> $twigPlugins
     * @param array<string, mixed> $configTwigOptions
     */
    protected function createTestDouble(
        array $loaderPlugins = [],
        array $twigPlugins = [],
        ?FilterExtenderInterface $filterExtender = null,
        array $configTwigOptions = [],
    ): object {
        $configMock = $this->createMock(TwigConfig::class);
        $configMock->method('getTwigOptions')->willReturn($configTwigOptions);

        $plugin = new class extends TwigApplicationPlugin {
            public function testGetChainLoader(): ChainLoader
            {
                return $this->getChainLoader();
            }

            public function testExtendTwig(Environment $twig, ContainerInterface $container): Environment
            {
                return $this->extendTwig($twig, $container);
            }

            /**
             * @return array<string, mixed>
             */
            public function testGetTwigOptions(ContainerInterface $container): array
            {
                return $this->getTwigOptions($container);
            }

            public function testAddGlobalTwigFilters(Environment $twig): Environment
            {
                return $this->addGlobalTwigFilters($twig);
            }

            public function testAddConfigurationValueTwigFunction(Environment $twig): Environment
            {
                return $this->addConfigurationValueTwigFunction($twig);
            }

            public function testAddConfigurationValuesTwigFunction(Environment $twig): Environment
            {
                return $this->addConfigurationValuesTwigFunction($twig);
            }
        };

        $plugin->setConfig($configMock);
        $plugin->setFactory($this->createFactoryMock($loaderPlugins, $twigPlugins, $filterExtender));

        return $plugin;
    }

    /**
     * @param array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface> $loaderPlugins
     * @param array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface> $twigPlugins
     */
    protected function createFactoryMock(
        array $loaderPlugins = [],
        array $twigPlugins = [],
        ?FilterExtenderInterface $filterExtender = null,
    ): TwigCommunicationFactory {
        $factoryMock = $this->createMock(TwigCommunicationFactory::class);
        $factoryMock->method('createChainLoader')->willReturn(new ChainLoader());
        $factoryMock->method('getTwigLoaderPlugins')->willReturn($loaderPlugins);
        $factoryMock->method('getTwigPlugins')->willReturn($twigPlugins);
        $factoryMock->method('createFilterExtender')->willReturn($filterExtender ?? $this->createPassThroughFilterExtender());
        $factoryMock->method('createConfigurationValueTwigFunction')->willReturn(
            new TwigFunction(static::TWIG_FUNCTION_NAME, static fn (): mixed => null),
        );
        $factoryMock->method('createConfigurationValuesTwigFunction')->willReturn(
            new TwigFunction(static::TWIG_FUNCTION_NAME_VALUES, static fn (): array => []),
        );

        return $factoryMock;
    }

    protected function createPassThroughFilterExtender(): FilterExtenderInterface
    {
        $filterExtenderMock = $this->createMock(FilterExtenderInterface::class);
        $filterExtenderMock->method('extend')->willReturnArgument(0);

        return $filterExtenderMock;
    }

    protected function createContainerMock(string $charset = 'UTF-8', ?bool $debug = null): ContainerInterface
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('has')
            ->willReturnMap([[TwigApplicationPlugin::SERVICE_DEBUG, $debug !== null]]);
        $containerMock->method('get')
            ->willReturnMap([
                [TwigApplicationPlugin::SERVICE_CHARSET, $charset],
                [TwigApplicationPlugin::SERVICE_DEBUG, $debug],
            ]);

        return $containerMock;
    }

    protected function invokeTwigServiceClosure(array $twigPlugins = []): Environment
    {
        [$twig] = $this->invokeTwigServiceClosureWithContainer($twigPlugins);

        return $twig;
    }

    /**
     * @param array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface> $twigPlugins
     *
     * @return array{\Twig\Environment, \Spryker\Service\Container\ContainerInterface}
     */
    protected function invokeTwigServiceClosureWithContainer(array $twigPlugins = []): array
    {
        $configMock = $this->createMock(TwigConfig::class);
        $configMock->method('getTwigOptions')->willReturn([]);

        $plugin = new TwigApplicationPlugin();
        $plugin->setConfig($configMock);
        $plugin->setFactory($this->createFactoryMock(twigPlugins: $twigPlugins));

        $capturedClosure = null;

        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('set')
            ->willReturnCallback(static function (string $key, callable $factory) use (&$capturedClosure): void {
                $capturedClosure = $factory;
            });
        $containerMock->method('has')->willReturn(false);
        $containerMock->method('get')
            ->willReturnMap([[TwigApplicationPlugin::SERVICE_CHARSET, 'UTF-8']]);

        $plugin->provide($containerMock);

        return [$capturedClosure($containerMock), $containerMock];
    }
}
