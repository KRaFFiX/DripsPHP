<?php


/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 01.07.15 - 17:00.
 */
namespace DripsPHP\API;

use DripsPHP\CLI\Console;
use DripsPHP\CLI\ICMD;

/**
 * Class Drips.
 *
 * CLI for updating the drips framework
 */
abstract class Drips implements ICMD
{
    /**
     * checks if there is a new update for drips available
     */
    public static function check()
    {
        $updater = new Updater();
        if($updater->hasUpdate()){
            Console::success("Update is available.");
        } else {
            Console::error("No update available.");
        }
    }

    /**
     * updates drips to the newest available version
     */
    public static function update()
    {
        $updater = new Updater();
        if($updater->hasUpdate()){
            if($updater->download()){
                Console::success("Drips has been updated.");
            } else {
                Console::error("Drips could not been updated.");
            }
        } else {
            Console::error("No update available.");
        }
    }

    /**
     * shows help for the Drips command.
     */
    public static function help()
    {
        Console::writeln('You can check if there is a new update available for drips:');
        Console::setColor('blue');
        Console::writeln('php drips check:Drips');
        Console::resetColors();
        Console::writeln('');
        Console::writeln('You can update drips to the newest version:');
        Console::setColor('blue');
        Console::writeln('php drips update:Drips');
        Console::resetColors();
    }
}
