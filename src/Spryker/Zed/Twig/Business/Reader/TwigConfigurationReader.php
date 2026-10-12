<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Reader;

use Generated\Shared\Transfer\TwigConfigurationResponseTransfer;
use Spryker\Zed\Twig\TwigConfig;

class TwigConfigurationReader implements TwigConfigurationReaderInterface
{
    public function __construct(protected TwigConfig $twigConfig)
    {
    }

    public function getTwigConfiguration(): TwigConfigurationResponseTransfer
    {
        return (new TwigConfigurationResponseTransfer())
            ->setYvesThemeName($this->twigConfig->getYvesThemeName())
            ->setYvesThemeNameDefault($this->twigConfig->getYvesThemeNameDefault());
    }
}
