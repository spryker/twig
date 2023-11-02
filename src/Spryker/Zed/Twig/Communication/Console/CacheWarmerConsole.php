<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 */
class CacheWarmerConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'twig:cache:warmer';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will generate a cache file for twig templates';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->warmUpCache();

        return static::CODE_SUCCESS;
    }
}
