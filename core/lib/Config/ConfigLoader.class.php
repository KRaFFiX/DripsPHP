<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 03.02.15 - 12:17.
 */
namespace DripsPHP\Config;

use Exception;

/**
 * Class ConfigLoader.
 *
 * responsible for loading ini-files for configuration
 */
class ConfigLoader
{
    protected $configuration = array();
    protected $directory;
    protected $defaultConfig;

    /**
     * loads config from an directory.
     *
     * @param string $dir
     * @param string $defaultConfig
     *
     * @throws ConfigDirectoryNotFound
     */
    public function __construct($dir = 'core/config', $defaultConfig = 'default')
    {
        if (!is_dir($dir)) {
            throw new ConfigDirectoryNotFound();
        }
        $this->directory = $dir;
        $this->defaultConfig = $defaultConfig;
        $this->loadDefault();
    }

    /**
     * loads default configuration.
     */
    protected function loadDefault()
    {
        $this->load($this->defaultConfig);
    }

    /**
     * loads an specific configuration.
     *
     * @param $mode
     *
     * @return bool
     */
    public function load($mode = null)
    {
        if ($mode !== null) {
            $path = $this->directory.'/'.$mode.'.ini';
            if (file_exists($path)) {
                $config = parse_ini_file($path);
                $this->configuration = array_merge($this->configuration, $config);

                return true;
            }
        }

        return false;
    }

    /**
     * returns the configuration as an array.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->configuration;
    }
}

class ConfigDirectoryNotFound extends Exception
{
}
