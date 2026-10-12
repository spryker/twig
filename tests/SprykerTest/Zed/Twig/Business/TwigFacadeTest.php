<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\TwigBusinessFactory;
use Spryker\Zed\Twig\Business\TwigFacade;
use SprykerTest\Zed\Twig\TwigBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Facade
 * @group TwigFacadeTest
 * Add your own group annotations below this line
 */
class TwigFacadeTest extends Unit
{
    protected const string YVES_THEME_NAME = 'b2b';

    protected const string YVES_THEME_NAME_DEFAULT = 'default';

    protected TwigBusinessTester $tester;

    public function testWarmUpCacheDelegatesToCacheWarmerModel(): void
    {
        $factoryMock = $this->getFactoryMock();
        $twigFacade = new TwigFacade();
        $twigFacade->setFactory($factoryMock);

        $twigFacade->warmUpCache();
    }

    public function testGetTwigConfigurationReturnsConfiguredAndDefaultYvesThemes(): void
    {
        // Arrange
        $this->tester->setConfig(TwigConstants::YVES_THEME, static::YVES_THEME_NAME);

        // Act
        $twigConfigurationResponseTransfer = $this->tester->getLocator()->twig()->facade()->getTwigConfiguration();

        // Assert
        $this->assertSame(static::YVES_THEME_NAME, $twigConfigurationResponseTransfer->getYvesThemeName());
        $this->assertSame(static::YVES_THEME_NAME_DEFAULT, $twigConfigurationResponseTransfer->getYvesThemeNameDefault());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\TwigBusinessFactory
     */
    protected function getFactoryMock(): TwigBusinessFactory
    {
        $mockBuilder = $this->getMockBuilder(TwigBusinessFactory::class)
            ->onlyMethods(['createCacheWarmer']);

        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())->method('createCacheWarmer')->willReturn($this->getCacheWarmerMock());

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    protected function getCacheWarmerMock(): CacheWarmerInterface
    {
        $mockBuilder = $this->getMockBuilder(CacheWarmerInterface::class);

        return $mockBuilder->getMock();
    }
}
