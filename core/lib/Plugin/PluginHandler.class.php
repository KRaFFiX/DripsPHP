<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.05.15 - 16:02.
 */
namespace DripsPHP\Plugin;

/**
 * Class PluginHandler.
 *
 * used for managing the plugin system of DripsPHP
 */
abstract class PluginHandler
{
    protected static $plugins = array();
    protected static $loaded = false;
    protected static $changed = false;

    /**
     * enable plugin named $plugin_name.
     *
     * @param $plugin_name
     *
     * @return bool
     */
    public static function enable($plugin_name)
    {
        if (!self::isEnabled($plugin_name) && self::exists($plugin_name)) {
            self::$plugins[] = $plugin_name;
            self::$changed = true;

            return true;
        }

        return false;
    }

    /**
     * disable plugin named $plugin_name.
     *
     * @param $plugin_name
     *
     * @return bool
     */
    public static function disable($plugin_name)
    {
        if (self::isEnabled($plugin_name)) {
            $key = array_search($plugin_name, self::getPlugins());
            unset(self::$plugins[$key]);
            self::$changed = true;

            return true;
        }

        return false;
    }

    /**
     * returns if the plugin named $plugin_name is enabled.
     *
     * @param $plugin_name
     *
     * @return bool
     */
    public static function isEnabled($plugin_name)
    {
        return in_array($plugin_name, self::getPlugins());
    }

    /**
     * returns all active plugins.
     *
     * @return array
     */
    public static function getPlugins()
    {
        if (!self::$loaded) {
            self::$plugins = json_decode(file_get_contents(__DIR__.'/ActivePlugins.json'), true);
        }

        return self::$plugins;
    }

    /**
     * saves active plugins to load them if needed.
     *
     * @return bool
     */
    public static function save()
    {
        if (!self::$changed) {
            return true;
        }

        return file_put_contents(__DIR__.'/ActivePlugins.json', json_encode(self::$plugins)) !== false;
    }

    /**
     * returns all plugins which are available.
     *
     * @return array
     */
    public static function getAllPlugins()
    {
        $plugins = array();
        $plugindir = 'plugins';
        foreach (scandir($plugindir) as $dir) {
            if (!in_array($dir, ['.', '..']) && self::exists($dir)) {
                $plugins[] = $dir;
            }
        }

        return $plugins;
    }

    /**
     * returns if plugin named $plugin_name exists.
     *
     * @param $plugin_name
     *
     * @return bool
     */
    public static function exists($plugin_name)
    {
        $dir = "plugins/$plugin_name";
        $file = "plugins/$plugin_name/Plugin.php";

        return is_dir($dir) && file_exists($file);
    }

    /**
     * returns the class of the plugin named $plugin_name.
     * if plugin does not exist it will return null.
     *
     * @param $plugin_name
     *
     * @return string|null
     */
    public static function getClass($plugin_name)
    {
        $path = "plugins/$plugin_name/Plugin.php";
        if (self::exists($plugin_name)) {
            require_once $path;
            $classname = "plugins\\$plugin_name\\Plugin";

            return $classname;
        }
        self::disable($plugin_name);

        return;
    }

    /**
     * loads all active plugins.
     */
    public static function load()
    {
        foreach (self::getPlugins() as $plugin_name) {
            $plugin = self::getClass($plugin_name);
            $obj = new $plugin();
        }
    }
}
