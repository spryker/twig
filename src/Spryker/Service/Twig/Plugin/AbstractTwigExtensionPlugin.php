<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Shared\Twig\TwigExtensionInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Twig\Environment;

abstract class AbstractTwigExtensionPlugin extends AbstractPlugin implements TwigPluginInterface, TwigExtensionInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addExtension($this);

        return $twig;
    }

    /**
     * @deprecated since 1.23 (to be removed in 2.0), implement \Twig\Extension\InitRuntimeInterface instead.
     *
     * @return void
     */
    public function initRuntime(Environment $environment)
    {
    }

    /**
     * @return array<\Twig\TokenParser\TokenParserInterface>
     */
    public function getTokenParsers(): array
    {
        return [];
    }

    /**
     * @return array<\Twig\NodeVisitor\NodeVisitorInterface>
     */
    public function getNodeVisitors(): array
    {
        return [];
    }

    /**
     * @return array<\Twig\TwigFilter>
     */
    public function getFilters(): array
    {
        return [];
    }

    /**
     * @return array<\Twig\TwigTest>
     */
    public function getTests(): array
    {
        return [];
    }

    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        return [];
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @psalm-return array{
     *     array<string, array{precedence: int, precedence_change?: \Twig\ExpressionParser\PrecedenceChange, class: class-string<\Spryker\Service\Twig\Plugin\AbstractUnary>}>,
     *     array<string, array{precedence: int, precedence_change?: \Spryker\Service\Twig\Plugin\PrecedenceChange, class?: class-string<\Spryker\Service\Twig\Plugin\AbstractBinary>, associativity: \Spryker\Service\Twig\Plugin\ExpressionParser::OPERATOR_*}>
     * }
     *
     * @return array<array>
     */
    public function getOperators(): array
    {
        if (version_compare(Environment::VERSION, '3.21.0', '>=')) {
            return [[], []];
        }

        return [];
    }

    /**
     * @deprecated since 1.23 (to be removed in 2.0), implement Twig\Extension\GlobalsInterface instead.
     *
     * @return array
     */
    public function getGlobals(): array
    {
        return [];
    }

    /**
     * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
     *
     * @return string
     */
    public function getName()
    {
        return static::class;
    }
}
