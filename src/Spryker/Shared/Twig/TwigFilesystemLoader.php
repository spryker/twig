<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Throwable;
use Twig\Environment;
use Twig\Source;

if (Environment::MAJOR_VERSION < 3) {
    class TwigFilesystemLoader extends BaseTwigFilesystemLoader
    {
        /**
         * @param string $path
         * @param string $namespace
         *
         * @return void
         */
        public function addPath($path, $namespace = '__main__')
        {
            $this->paths[] = rtrim($path, '/\\');
        }

        /**
         * @param string $name
         * @param int $time
         *
         * @return bool
         */
        public function isFresh($name, $time)
        {
            return filemtime($this->findTemplate($name)) <= $time;
        }

        /**
         * @param string $name
         *
         * @return string
         */
        public function getCacheKey($name)
        {
            return $this->findTemplate($name);
        }

        /**
         * @param string $name
         *
         * @return \Twig\Source
         */
        public function getSourceContext($name)
        {
            $path = $this->findTemplate($name);
            $originalCode = file_get_contents($path);
            // Collapse 2+ leading whitespace chars to one space instead of stripping completely.
            // Twig 3 always eats the \n after %} block tags; keeping one space ensures it
            // acts as a separator so adjacent HTML attributes are never concatenated.
            $spacelessCode = preg_replace('/^[ \t]{2,}/m', ' ', $originalCode);
            $spacelessCode = preg_replace('/\n{2,}/', "\n", $spacelessCode);

            return new Source($spacelessCode, $name, $path);
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function exists($name)
        {
            if ($this->cache->has($name) && $this->cache->get($name)) {
                return true;
            }

            try {
                return $this->findTemplate($name) !== null;
            } catch (Throwable $throwable) {
                return false;
            }
        }
    }
} else {
    class TwigFilesystemLoader extends BaseTwigFilesystemLoader
    {
        /**
         * @param string $path
         * @param string $namespace
         *
         * @return void
         */
        public function addPath(string $path, string $namespace = '__main__'): void
        {
            $this->paths[] = rtrim($path, '/\\');
        }

        /**
         * @param string $name
         * @param int $time
         *
         * @return bool
         */
        public function isFresh(string $name, int $time): bool
        {
            return filemtime($this->findTemplate($name)) <= $time;
        }

        /**
         * @param string $name
         *
         * @return string
         */
        public function getCacheKey(string $name): string
        {
            return $this->findTemplate($name);
        }

        /**
         * @param string $name
         *
         * @return \Twig\Source
         */
        public function getSourceContext(string $name): Source
        {
            $path = $this->findTemplate($name);

            $originalCode = file_get_contents($path);
            // Collapse 2+ leading whitespace chars to one space instead of stripping completely.
            // Twig 3 always eats the \n after %} block tags; keeping one space ensures it
            // acts as a separator so adjacent HTML attributes are never concatenated.
            $spacelessCode = preg_replace('/^[ \t]{2,}/m', ' ', $originalCode);
            $spacelessCode = preg_replace('/\n{2,}/', "\n", $spacelessCode); // Collapses consecutive blank lines into one

            return new Source($spacelessCode, $name, $path);
        }

        /**
         * @param string $name
         *
         * @return bool
         */
        public function exists(string $name): bool
        {
            if ($this->cache->has($name) && $this->cache->get($name)) {
                return true;
            }

            try {
                return $this->findTemplate($name) !== null;
            } catch (Throwable $throwable) {
                return false;
            }
        }
    }
}
