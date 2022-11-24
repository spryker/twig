<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extender;

use Twig\Environment;

interface FilterExtenderInterface
{
    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment;
}
