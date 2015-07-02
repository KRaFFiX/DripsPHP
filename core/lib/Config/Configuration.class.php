<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 03.02.15 - 12:16.
 */
namespace DripsPHP\Config;

/**
 * Class Configuration.
 *
 * used for converting ini-array to configuration object
 */
abstract class Configuration
{
    protected static $config = array();

    /**
     * returns if $key exists in config.
     *
     * @param $key
     *
     * @return bool
     */
    public static function has($key)
    {
        return array_key_exists($key, self::$config);
    }

    /**
     * returns the value if the $key exists in config, otherwise null will be
     * returned.
     *
     * @param $key
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return self::$config[$key];
        }

        return;
    }

    /**
     * sets temporary a specific value in config.
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }

    /**
     * set config of the configuration (array).
     *
     * @param $config
     *
     * @return bool
     */
    public static function setConfig($config)
    {
        if (is_array($config)) {
            self::$config = $config;

            return true;
        }

        return false;
    }
}
