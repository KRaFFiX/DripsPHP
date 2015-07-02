<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 15.03.15 - 19:06.
 */
namespace DripsPHP\MVC;

use DripsPHP\CLI\ICMD;
use DripsPHP\CLI\Console;
use DripsPHP\Generators\PackageGenerator;

/**
 * Class Package.
 *
 * CLI managing packages in src directory
 */
abstract class Package implements ICMD
{
    /**
     * creates a new package with $name in src directory.
     *
     * @param $name
     */
    public static function create($name)
    {
        if (PackageGenerator::generate($name)) {
            Console::success('Package has successfully been created.');
        } else {
            Console::error('Package could not been created.');
        }
    }

    /**
     * shows help for package cmd.
     */
    public static function help()
    {
        Console::writeln('You can automatically generate packages.');
        Console::writeln('The following command allows you to generate a package in src directory named {name}:');
        Console::setColor('blue');
        Console::writeln('php drips create:Package {name}');
        Console::resetColors();
    }
}
