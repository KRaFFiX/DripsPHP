<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 02.02.15 - 14:11.
 */
namespace DripsPHP\CLI;

use DripsPHP\ClassLoader\Path;

abstract class Help implements ICMD
{
    /**
     * shows help.
     */
    public static function show()
    {
        Console::setColor('blue');
        Console::writeln('===== HELP =====');
        Console::resetColors();
        Console::writeln('If you want to execute a command you need to use following format:');
        Console::success('php drips {action}:{type} [param1] [param2] [...]');
        Console::setColor('blue');
        Console::writeln('===== CMDs =====');
        Console::resetColors();
        Console::writeln(sprintf('%15s | ', '{TYPE}').'{ACTION}');
        Console::writeln('----------------+---------------');
        $cmds = CMD::getAll();
        foreach ($cmds as $cmd) {
            $path = Path::getFromNamespace($cmd);
            $name = $path->getClassName();
            Console::setColor('purple');
            Console::write(sprintf('%15s ', $name));
            Console::resetColors();
            Console::writeln('| ');
            $class = $path->getClass();
            $methods = get_class_methods($class);
            sort($methods);
            foreach ($methods as $method) {
                Console::writeln(sprintf('%15s | ', '').$method);
            }
            Console::writeln('----------------+---------------');
        }
    }

    /**
     * alias for show function because of the ICMD interface.
     */
    public static function help()
    {
        self::show();
    }
}
