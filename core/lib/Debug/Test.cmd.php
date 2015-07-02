<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 22.02.15 - 11:58.
 */
namespace DripsPHP\Debug;

use DripsPHP\CLI\Console;
use DripsPHP\CLI\ICMD;
use Exception;

/**
 * Class Test.
 *
 * This class is a command for the DripsCLI. It serves to execute the unit tests
 * created and then display the result.
 */
abstract class Test implements ICMD
{
    protected static $tests = 0;
    protected static $successful = 0;
    protected static $fails = 0;

    /**
     * starts unit testing.
     * To hand over is either a directory in which all files are to be tested
     * (recursively), or a single file, which is to be tested.
     *
     * @param string $directory
     *
     * @throws TestDirectoryOrFileNotFound
     */
    public static function start($directory = 'test')
    {
        if (is_dir($directory)) {
            self::runTestDir($directory);
        } elseif (file_exists($directory)) {
            self::runTestFile($directory);
        } else {
            throw new TestDirectoryOrFileNotFound();
        }
        Console::writeln('------------------------------');
        Console::writeln('Tests: '.self::$tests);
        Console::success('Successful: '.self::$successful);
        Console::error('Fails: '.self::$fails);
        Console::resetColors();
    }

    /**
     * testing a specific directory (recursively).
     *
     * @param $dir
     */
    protected static function runTestDir($dir)
    {
        foreach (array_diff(scandir($dir), array('.', '..')) as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                self::runTestDir($path);
            } elseif (file_exists($path)) {
                $type = explode('.', $file);
                $type = array_pop($type);
                if (strtolower($type) == 'php') {
                    self::runTestFile($path);
                }
            }
        }
    }

    /**
     * testing a specific file.
     *
     * @param $file
     */
    protected static function runTestFile($file)
    {
        $name = basename($file, '.php');
        //Console::writeln("Testing: $name");
        $parts = explode('/', $file);
        array_pop($parts);
        $namespace = implode('\\', $parts).'\\'.$name;
        //Console::writeln("Loading: $file");
        require_once $file;
        //Console::writeln("Creating: $namespace");
        $obj = new $namespace();
        // except methods from UnitTest-Class
        $methods = array_diff(get_class_methods($namespace), get_class_methods(__NAMESPACE__.'\\UnitTest'));
        foreach ($methods as $method) {
            //Console::writeln("Executing: $method");
            $result = $obj->$method();
            self::$tests++;
            if ($result) {
                Console::setColor('green');
                Console::write('[Test successful]');
                self::$successful++;
            } else {
                Console::setColor('red');
                Console::write('[Test failed]    ');
                self::$fails++;
            }
            Console::resetColors();
            Console::writeln("$namespace::$method");
        }
    }

    /**
     * help method which is shown on cli.
     */
    public static function help()
    {
        Console::writeln('This command is used for executing unit tests.');
        Console::writeln('If you want to create an unit test you need to create a php class which inherits UnitTest.');
        Console::writeln('In this class you need to define functions which return true if the result is expected or false if it is not expected.');
        Console::writeln('For executing the unit tests you can use following command:');
        Console::setColor('blue');
        Console::writeln('php drips start:Test {path/to/file/or/directory}');
        Console::resetColors();
        Console::writeln('If a directory is given all php files will be tested');
    }
}

class TestDirectoryOrFileNotFound extends Exception
{
}
