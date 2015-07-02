<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.05.15 - 10:22.
 */
namespace DripsPHP\Plugin;

/**
 * Class Registration.
 *
 * used for register plugins for a special usage
 */
abstract class Registration
{
    protected static $plugins = array();

    /**
     * register plugin.
     *
     * @param $name
     * @param $class
     *
     * @return bool
     */
    public static function register($name, $class)
    {
        if (!array_key_exists($name, static::$plugins)) {
            static::$plugins[$name] = $class;

            return true;
        }

        return false;
    }

    /**
     * returns all registered plugins.
     *
     * @return array
     */
    public static function getAll()
    {
        return static::$plugins;
    }

    /**
     * returns if $name is already registered.
     *
     * @param $name
     *
     * @return bool
     */
    public static function has($name)
    {
        return array_key_exists($name, static::$plugins);
    }

    /**
     * returns the class of registered plugin named $name. If $name is not
     * registered it will return null.
     *
     * @param $name
     *
     * @return string|null
     */
    public static function get($name)
    {
        if (static::has($name)) {
            return static::$plugins[$name];
        }

        return;
    }
}
