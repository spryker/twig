<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache;

interface CacheWriterInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function write(array $data);
}
