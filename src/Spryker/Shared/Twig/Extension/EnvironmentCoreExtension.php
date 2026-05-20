<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Extension;

use ReflectionMethod;
use ReflectionNamedType;
use Throwable;
use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\TwigFilter;

class EnvironmentCoreExtension implements EnvironmentCoreExtensionInterface
{
    /**
     * @var array
     */
    protected const SYSTEM_FUNCTIONS = [
        'exec',
        'shell_exec',
        'system',
        'passthru',
        'popen',
        'proc_open',
        'eval',
        'assert',
        'create_function',
        'preg_replace', // с /e modifier
        'include',
        'include_once',
        'require',
        'require_once',
        'file_get_contents',
        'file_put_contents',
        'fopen',
        'fwrite',
        'fread',
        'unlink',
        'chmod',
        'chown',
        'curl_exec',
        'curl_multi_exec',
        'phpinfo',
        'base64_decode',
        'base64_encode',
        'mail',
        'header',
        'set_include_path',
        'ini_set',
        'dl',
        'putenv',
        'apache_setenv',
    ];

    /**
     * Cache for `CoreExtension::<method>()` signature lookups — whether the
     * method declares `bool $isSandboxed` as its second parameter.
     *
     * @var array<string, bool>
     */
    protected static array $coreExtensionNeedsIsSandboxed = [];

    public function extend(Environment $twig): Environment
    {
        foreach ($this->getFilters() as $filter) {
            $twig->addFilter($filter);
        }

        return $twig;
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return \CallbackFilterIterator|array
     */
    public function filter(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'filter')) {
            return $this->dispatchToCoreExtension($env, 'filter', $array, $arrow);
        }

        return twig_array_filter($env, $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return array
     */
    public function find(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        return $this->dispatchToCoreExtension($env, 'find', $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     *
     * @return array
     */
    public function map(Environment $env, $array, $arrow)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'map')) {
            return $this->dispatchToCoreExtension($env, 'map', $array, $arrow);
        }

        return twig_array_map($env, $array, $arrow);
    }

    /**
     * @param \Twig\Environment $env
     * @param array $array
     * @param \Closure $arrow
     * @param mixed|null $initial
     *
     * @return mixed|null
     */
    public function reduce(Environment $env, $array, $arrow, $initial = null)
    {
        if ($this->isDisallowedPhpFunction($arrow)) {
            return $array;
        }

        if (method_exists(CoreExtension::class, 'reduce')) {
            return $this->dispatchToCoreExtension($env, 'reduce', $array, $arrow, $initial);
        }

        return twig_array_reduce($env, $array, $arrow, $initial);
    }

    /**
     * @param \Closure|null $arrow
     *
     * @return bool
     */
    protected function isDisallowedPhpFunction($arrow): bool
    {
        return in_array($arrow, static::SYSTEM_FUNCTIONS);
    }

    /**
     * Forwards a call to `\Twig\Extension\CoreExtension::<method>()` using the
     * argument shape that matches the installed Twig version. Twig added a
     * `bool $isSandboxed` parameter at position 2 of `filter`/`find`/`map`/
     * `reduce` (Twig 4.x); on older versions the signature is unchanged. We
     * detect the parameter via reflection so the same Spryker module works
     * against both Twig ranges declared in composer.json
     * (`^2.16.1 || ^3.14.0`).
     *
     * @param \Twig\Environment $env
     * @param string $method
     * @param mixed ...$args
     *
     * @return mixed
     */
    protected function dispatchToCoreExtension(Environment $env, string $method, ...$args)
    {
        if ($this->coreExtensionNeedsIsSandboxed($method)) {
            return CoreExtension::$method($env, $this->resolveIsSandboxed($env), ...$args);
        }

        return CoreExtension::$method($env, ...$args);
    }

    protected function coreExtensionNeedsIsSandboxed(string $method): bool
    {
        if (array_key_exists($method, static::$coreExtensionNeedsIsSandboxed)) {
            return static::$coreExtensionNeedsIsSandboxed[$method];
        }

        try {
            $parameters = (new ReflectionMethod(CoreExtension::class, $method))->getParameters();
            $secondParameter = $parameters[1] ?? null;

            if ($secondParameter === null) {
                return static::$coreExtensionNeedsIsSandboxed[$method] = false;
            }

            $type = $secondParameter->getType();

            return static::$coreExtensionNeedsIsSandboxed[$method] = (
                $secondParameter->getName() === 'isSandboxed'
                && $type instanceof ReflectionNamedType
                && $type->getName() === 'bool'
            );
        } catch (Throwable) {
            return static::$coreExtensionNeedsIsSandboxed[$method] = false;
        }
    }

    protected function resolveIsSandboxed(Environment $env): bool
    {
        if (!$env->hasExtension(SandboxExtension::class)) {
            return false;
        }

        try {
            /** @var \Twig\Extension\SandboxExtension $sandbox */
            $sandbox = $env->getExtension(SandboxExtension::class);

            return $sandbox->isSandboxed();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @return array<\Twig\TwigFilter>
     */
    protected function getFilters(): array
    {
        $filters = [
            new TwigFilter('filter', [$this, 'filter'], ['needs_environment' => true]),
            new TwigFilter('map', [$this, 'map'], ['needs_environment' => true]),
            new TwigFilter('reduce', [$this, 'reduce'], ['needs_environment' => true]),
        ];

        if (method_exists(CoreExtension::class, 'find')) {
            $filters[] = new TwigFilter('find', [$this, 'find'], ['needs_environment' => true]);
        }

        return $filters;
    }
}
