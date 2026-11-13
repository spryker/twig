<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extender;

use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Twig resolves functions, filters and tests while compiling a template, so a callable that no registered
 * extension provides makes the whole template fail, even when the call sits in a branch that never renders.
 * The fallback callables registered here are consulted by Twig only after every registered callable missed:
 * an unknown function returns its first argument (or null), an unknown filter returns its input,
 * an unknown test evaluates to false (Twig 3.22.0 and later).
 */
class UndefinedCallableFallbackExtender implements UndefinedCallableFallbackExtenderInterface
{
    protected const string OPTION_IS_VARIADIC = 'is_variadic';

    protected const string METHOD_REGISTER_UNDEFINED_TEST_CALLBACK = 'registerUndefinedTestCallback';

    public function __construct(protected bool $isEnabled)
    {
    }

    public function extend(Environment $twig): Environment
    {
        if (!$this->isEnabled) {
            return $twig;
        }

        $twig->registerUndefinedFunctionCallback(fn (string $name): TwigFunction => $this->createFallbackFunction($name));
        $twig->registerUndefinedFilterCallback(fn (string $name): TwigFilter => $this->createFallbackFilter($name));
        $this->registerUndefinedTestFallback($twig);

        return $twig;
    }

    protected function registerUndefinedTestFallback(Environment $twig): void
    {
        // Twig provides the undefined test callback since 3.22.0 only; older versions keep failing on unknown tests.
        if (!method_exists($twig, static::METHOD_REGISTER_UNDEFINED_TEST_CALLBACK)) {
            return;
        }

        $twig->registerUndefinedTestCallback(fn (string $name): TwigTest => $this->createFallbackTest($name));
    }

    protected function createFallbackFunction(string $name): TwigFunction
    {
        // Variadic arguments are declared as a trailing array, the only form Twig 2 and Twig 3 both accept.
        return new TwigFunction(
            $name,
            static fn (mixed $value = null, array $arguments = []): mixed => $value,
            [static::OPTION_IS_VARIADIC => true],
        );
    }

    protected function createFallbackFilter(string $name): TwigFilter
    {
        return new TwigFilter(
            $name,
            static fn (mixed $value, array $arguments = []): mixed => $value,
            [static::OPTION_IS_VARIADIC => true],
        );
    }

    protected function createFallbackTest(string $name): TwigTest
    {
        return new TwigTest(
            $name,
            static fn (mixed $value = null, array $arguments = []): bool => false,
            [static::OPTION_IS_VARIADIC => true],
        );
    }
}
