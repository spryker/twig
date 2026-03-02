<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extension;

use Twig\Environment;

interface EnvironmentCoreExtensionInterface
{
    public function extend(Environment $twig): Environment;
}
