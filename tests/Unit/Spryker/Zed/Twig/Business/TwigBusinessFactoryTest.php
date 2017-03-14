<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Twig\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\TwigBusinessFactory;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group Business
 * @group TwigBusinessFactoryTest
 */
class TwigBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateCacheWarmerReturnsCacheWarmerInterface()
    {
        $twigBusinessFactory = new TwigBusinessFactory();
        $this->assertInstanceOf(CacheWarmerInterface::class, $twigBusinessFactory->createCacheWarmer());
    }

}
