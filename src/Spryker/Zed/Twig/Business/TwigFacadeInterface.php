<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business;

use Generated\Shared\Transfer\TwigConfigurationResponseTransfer;

/**
 * @method \Spryker\Zed\Twig\Business\TwigBusinessFactory getFactory()
 */
interface TwigFacadeInterface
{
    /**
     * Specification:
     * - Creates twig template path cache file for all applied applications
     *
     * @api
     *
     * @return void
     */
    public function warmUpCache();

    /**
     * Specification:
     * - Returns the Twig configuration values.
     * - Populates `TwigConfigurationResponseTransfer.yvesThemeName` with the Yves theme configured by `TwigConstants::YVES_THEME`, an empty string when not configured.
     * - Populates `TwigConfigurationResponseTransfer.yvesThemeNameDefault` with the Yves theme used as a fallback for the configured theme.
     *
     * @api
     */
    public function getTwigConfiguration(): TwigConfigurationResponseTransfer;
}
