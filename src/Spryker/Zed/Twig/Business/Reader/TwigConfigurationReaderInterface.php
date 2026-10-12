<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Reader;

use Generated\Shared\Transfer\TwigConfigurationResponseTransfer;

interface TwigConfigurationReaderInterface
{
    public function getTwigConfiguration(): TwigConfigurationResponseTransfer;
}
