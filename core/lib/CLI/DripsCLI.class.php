<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 02.02.15 - 13:22.
 */
namespace DripsPHP\CLI;

/**
 * Class DripsCLI.
 *
 * This class is responsible for mangaging the CLI of DripsPHP
 */
abstract class DripsCLI
{
    /**
     * initializes the CLI and executes the right cmd from args.
     *
     * @param $args
     */
    public static function init($args)
    {
        if (count($args) >= 2) {
            $action = $args[1];
            $params = array();
            for ($i = 2; $i < count($args); $i++) {
                $params[] = $args[$i];
            }
            switch ($action) {
                case '-h':
                case '-help':
                case 'help':
                    self::showHelp();
                    break;
                default:
                    self::execute($action, $params);
                    break;
            }
        } else {
            self::showHelp();
        }
    }

    /**
     * executes help command.
     */
    protected static function showHelp()
    {
        self::execute('show:Help');
    }

    /**
     * executes a command with parameters (optional).
     *
     * @param $cmd
     * @param array $params
     *
     * @return bool
     */
    protected static function execute($cmd, $params = array())
    {
        $split = explode(':', $cmd);
        if (count($split) == 2) {
            $classname = $split[1];
            $method = $split[0];
            // registered CMDs
            self::registerCMDs();
            if (CMD::has($classname)) {
                $class = CMD::get($classname);
                $classMethod = $class.'::'.$method;
                if (method_exists($class, $method)) {
                    call_user_func_array($classMethod, $params);

                    return true;
                } else {
                    return self::execute("help:$classname");
                }
            }
            Console::error("Command: $cmd not found!");
        }

        return false;
    }

    /**
     * This function automatically registers all the CMDs it can be found in the
     * complete system.
     *
     * @param  $dir
     */
    protected static function registerCMDs($dir = '.')
    {
        foreach (scandir($dir) as $file) {
            if (substr($file, 0, 1) != '.') {
                $path = "$dir/$file";
                if (is_dir($path)) {
                    self::registerCMDs($path);
                } else {
                    if (preg_match("/\.cmd\.php/", $file)) {
                        // register CMD
                        $length = strlen($file) - $length2 = strlen('.cmd.php');
                        $name = substr($file, 0, $length);
                        $classname = substr(str_replace('/', '\\', str_replace('core/lib', 'DripsPHP', $dir)).'\\'.$name, 1);
                        CMD::register($name, $classname);
                    }
                }
            }
        }
    }
}
