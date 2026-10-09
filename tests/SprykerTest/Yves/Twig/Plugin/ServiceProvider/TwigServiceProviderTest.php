<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use Silex\SprykerStubInterface;
use Spryker\Yves\Twig\Plugin\ServiceProvider\TwigServiceProvider;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group Plugin
 * @group ServiceProvider
 * @group TwigServiceProviderTest
 * Add your own group annotations below this line
 */
class TwigServiceProviderTest extends Unit
{
    /**
     * Both cases need the real `Silex\Provider\TwigServiceProvider` to populate the `twig.*`
     * services that `Spryker\Yves\Twig\...\TwigServiceProvider` then extends. `spryker/silex` 3.x
     * ships stubs instead, so there is nothing left to exercise.
     *
     * Detected via the marker interface rather than `class_exists()`, which is still true for a stub.
     *
     * @return void
     */
    protected function _before(): void
    {
        if (is_a(Application::class, SprykerStubInterface::class, true)) {
            $this->markTestSkipped(
                'Silex has been removed; spryker/silex 3.x ships stubs only. Pin spryker/silex:^2.0.0 to run this test.',
            );
        }
    }

    public function testRegisterAddsFilesystemLoaderToApplication(): void
    {
        $application = new Application();

        $twigServiceProvider = new SilexTwigServiceProvider();
        $twigServiceProvider->register($application);

        $twigServiceProvider = new TwigServiceProvider();
        $twigServiceProvider->register($application);

        $this->assertArrayHasKey('twig.loader.yves', $application);
    }

    public function testBootDoesNothing(): void
    {
        $application = new Application();

        $twigServiceProvider = new SilexTwigServiceProvider();
        $twigServiceProvider->register($application);

        $twigServiceProvider = new TwigServiceProvider();
        $twigServiceProvider->boot($application);

        $this->assertArrayNotHasKey('twig.loader.yves', $application);
    }
}
