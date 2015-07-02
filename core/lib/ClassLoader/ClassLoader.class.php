<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 13:26.
 */
namespace DripsPHP\ClassLoader;

use DripsPHP\Plugin\PluginHandler;

/**
 * Class ClassLoader.
 *
 * The ClassLoader is responsible for the automatic loading of classes to be used.
 */
abstract class ClassLoader
{
    /**
     * This function is the main method of the ClassLoader. Parameter is a
     * complete namespace. Where it can be the namespace resolves to a file path
     * and the file exists, the class is loaded automatically when it is needed.
     *
     * @param $class
     *
     * @return bool
     */
    public static function load($class)
    {
        return self::loadClass(self::getClassFile($class));
    }

    /**
     * This function takes the resolution of a namespace with class in a file
     * path. It is also checked whether the file exists.
     * It will return false if there is no valid path could be found.
     *
     * @param $class
     *
     * @return string|false
     */
    public static function getClassFile($class)
    {
        $path = self::loadCMD($class);
        if ($path === false) {
            $path = self::loadCore($class);
            if ($path === false) {
                $path = self::loadSrc($class);
                if ($path === false) {
                    $path = self::loadPlugin($class);
                }
            }
        }

        return $path;
    }

    /**
     * This function takes the resolution of all PHP classes, which are located
     * in the core / lib directory. Instead of the namespace core / lib is, this
     * is namely DripsPHP. In addition, the classes in the core / lib directory
     * need the ending .class.php so that they can be loaded by the class loader.
     * If the class can not be resolved, or the file does not exist, it will
     * return false.
     *
     * @param $class
     *
     * @return string|false
     */
    private static function loadCore($class)
    {
        if (strpos($class, 'DripsPHP') !== false) {
            $path = str_replace('\\', '/', str_replace('DripsPHP', 'core\\lib\\', $class)).'.class.php';

            if (self::classExists($path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * This function is performed by the resolution of all namespaces and classes
     * that are located in the src directory.
     * If the class can not be resolved, or the file does not exist, it will
     * return false.
     *
     * @param $class
     *
     * @return string|false
     */
    private static function loadSrc($class)
    {
        $path = 'src/'.str_replace('\\', '/', $class).'.php';

        if (self::classExists($path)) {
            return $path;
        }

        return false;
    }

    /**
     * This class resolves classes for plugins. The php-files of plugin will only
     * been loaded if the plugin is enabled.
     * If the class can not be resolved, or the file does not exist, it will
     * return false.
     *
     * @param $class
     *
     * @return string|false
     */
    private static function loadPlugin($class)
    {
        // Block connections to the plugin if it is not enabled
        $parts = explode('\\', $class);
        //$className = array_pop($parts);
        if (count($parts) < 2) {
            return false;
        }
        $pluginName = $parts[1];
        if (!PluginHandler::isEnabled($pluginName)) {
            return false;
        }

        $path = str_replace('\\', '/', $class).'.php';

        if (self::classExists($path)) {
            return $path;
        }

        return false;
    }

    /**
     * Loading DripsCLI CMDs. This can be in the system everywhere, but require
     * the file extension .cmd.php
     * If the class can not be resolved, or the file does not exist, it will
     * return false.
     *
     * @param $class
     *
     * @return string|false
     */
    private static function loadCMD($class)
    {
        $path = str_replace('\\', '/', str_replace('DripsPHP', 'core\\lib\\', $class).'.cmd.php');
        if (self::classExists($path)) {
            return $path;
        }

        return false;
    }

    /**
     * Loads the specified file. Returns True or False whether or not the class
     * exists.
     *
     * @param $path
     *
     * @return bool
     */
    private static function loadClass($path)
    {
        if (self::classExists($path)) {
            require_once $path;

            return true;
        }

        return false;
    }

    /**
     * This function can be checked whether the specified file exists or not.
     *
     * @param $path
     *
     * @return bool
     */
    private static function classExists($path)
    {
        return is_file($path);
    }
}
