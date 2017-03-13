<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache\Filesystem;

use Spryker\Shared\Twig\Cache\CacheInterface;

class PathCache implements CacheInterface
{

    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @param string $cacheFilePath
     * @param bool $enabled
     */
    public function __construct($cacheFilePath, $enabled)
    {
        $this->cacheFilePath = $cacheFilePath;
        $this->enabled = $enabled;
        $this->cache = $this->loadCache();
    }

    /**
     * @return array
     */
    protected function loadCache()
    {
        if (!file_exists($this->cacheFilePath)) {
            return [];
        }

        return include($this->cacheFilePath);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        if (!$this->enabled) {
            return false;
        }

        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     *
     * @return bool|string
     */
    public function get($key)
    {
        if (!$this->enabled || !$this->has($key)) {
            return false;
        }

        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        if (!$this->enabled || !$this->has($key)) {
            return false;
        }

        return ($this->cache[$key] !== false);
    }

    public function __destruct()
    {
        if (count($this->cache) === 0) {
            return;
        }

        $cacheFileContent = '<?php return [' . PHP_EOL;
        foreach ($this->cache as $key => $value) {
            $cacheFileContent .= '    \'' . $key . '\' => ' . var_export($value, true) . ',' . PHP_EOL;
        }
        $cacheFileContent .= '];';

        $directory = dirname($this->cacheFilePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

//        file_put_contents($this->cacheFilePath, $cacheFileContent);
    }

}