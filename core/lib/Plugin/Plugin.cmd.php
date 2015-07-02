<?php


/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.05.15 - 11:14.
 */
namespace DripsPHP\Plugin;

use DripsPHP\CLI\Console;
use DripsPHP\CLI\ICMD;
use ReflectionClass;

/**
 * Class Plugin.
 *
 * CLI for enable and disable plugins
 */
abstract class Plugin implements ICMD
{
    /**
     * shows all plugins are available and if they are enabled or not.
     */
    public static function listAll()
    {
        $plugins = PluginHandler::getAllPlugins();
        foreach ($plugins as $plugin) {
            if (PluginHandler::isEnabled($plugin)) {
                Console::success($plugin);
            } else {
                Console::error($plugin);
            }
        }
    }

    /**
     * enables plugin $plugin_name.
     *
     * @param $plugin_name
     */
    public static function enable($plugin_name)
    {
        if (PluginHandler::enable($plugin_name)) {
            Console::success("The plugin ($plugin_name) has successfully been enabled.");
        } else {
            Console::error("The plugin ($plugin_name) could not been enabled.");
        }
    }

    /**
     * disables plugin $plugin_name.
     *
     * @param $plugin_name
     */
    public static function disable($plugin_name)
    {
        if (PluginHandler::disable($plugin_name)) {
            Console::success("The plugin ($plugin_name) has successfully been disabled.");
        } else {
            Console::error("The plugin ($plugin_name) could not been disabled.");
        }
    }

    /**
     * returns information about the plugin named $plugin_name.
     *
     * @param $plugin_name
     */
    public static function info($plugin_name)
    {
        $class = PluginHandler::getClass($plugin_name);
        Console::setColor('blue');
        Console::writeln("=== $plugin_name ===");
        Console::resetColors();
        $ref = new ReflectionClass($class);
        $constants = $ref->getConstants();
        foreach ($constants as $key => $val) {
            Console::setColor('blue');
            printf('%10s: ', $key);
            Console::resetColors();
            Console::writeln($val);
        }
        Console::setColor('blue');
        printf('%10s: ', 'Status');
        Console::resetColors();
        PluginHandler::isEnabled($plugin_name) ? Console::success('enabled') : Console::error('disabled');
    }

    /**
     * checks if there is a new update for the plugin named $plugin_name
     *
     * @param $plugin_name
     */
    public static function check($plugin_name)
    {
        if(PluginHandler::exists($plugin_name)){
            $updater = new Updater();
            if($updater->hasUpdate($plugin_name)){
                Console::success("Update is available.");
            } else {
                Console::error("No update available.");
            }
        } else {
            Console::error("Plugin not found.");
        }
    }

    /**
     * updates the plugin named $plugin_name
     *
     * @param $plugin_name
     */
    public static function update($plugin_name)
    {
        if(PluginHandler::exists($plugin_name)){
            $updater = new Updater();
            if($updater->hasUpdate($plugin_name)){
                self::install($plugin_name);
            } else {
                Console::error("No update available.");
            }
        } else {
            Console::error("Plugin not found.");
        }
    }

    /**
     * install a new plugin from store
     *
     * @param $plugin_name
     */
    public static function install($plugin_name)
    {
        $updater = new Updater();
        if($updater->download($plugin_name, $updater->getDownloadLink($plugin_name))){
            Console::success("Plugin has been installed.");
        } else {
            Console::error("Plugin could not been installed.");
        }
    }

    /**
     * shows help for the Plugin command.
     */
    public static function help()
    {
        Console::writeln('You can enable/disable plugins by using following command:');
        Console::setColor('blue');
        Console::writeln('php drips enable:Plugin {plugin_name}');
        Console::writeln('php drips disable:Plugin {plugin_name}');
        Console::resetColors();
        Console::writeln('');
        Console::writeln('You can list all plugins:');
        Console::setColor('blue');
        Console::writeln('php drips listAll:Plugin');
        Console::resetColors();
        Console::writeln('');
        Console::writeln('You can return information about a plugin:');
        Console::setColor('blue');
        Console::writeln('php drips info:Plugin {plugin_name}');
        Console::resetColors();
        Console::writeln('You can check if there is a new update available for a plugin:');
        Console::setColor('blue');
        Console::writeln('php drips check:Plugin {plugin_name}');
        Console::resetColors();
        Console::writeln('');
        Console::writeln('You can update the plugin to the newest version:');
        Console::setColor('blue');
        Console::writeln('php drips update:Plugin {plugin_name}');
        Console::resetColors();
        Console::writeln('You can install a plugin from the store:');
        Console::setColor('blue');
        Console::writeln('php drips install:Plugin {plugin_name}');
        Console::resetColors();
    }
}
