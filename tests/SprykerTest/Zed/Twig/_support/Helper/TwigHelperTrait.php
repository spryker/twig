<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Helper;

use Codeception\Module;

trait TwigHelperTrait
{
    protected function getTwigHelper(): TwigHelper
    {
        /** @var \SprykerTest\Zed\Twig\Helper\TwigHelper $twigHelper */
        $twigHelper = $this->getModule('\\' . TwigHelper::class);

        return $twigHelper;
    }

    abstract protected function getModule(string $name): Module;
}
