<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Twig;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\Twig\TwigConfig;

class ConfigurationValueTwigFunctionProvider extends TwigFunctionProvider
{
    protected const string FUNCTION_NAME = 'configurationValue';

    public function __construct(protected TwigConfig $config)
    {
    }

    public function getFunctionName(): string
    {
        return static::FUNCTION_NAME;
    }

    public function getFunction(): callable
    {
        return function (string $key, mixed $default = null): mixed {
            return $this->config->getModuleConfig($key, $default);
        };
    }
}
