<?php

/**
 * Created by Prowect.
 * User: Raffael Kessler
 * Date: 24.07.14
 * Time: 10:47.
 */
namespace DripsPHP\CLI;

/**
 * Class Console.
 *
 * This class is used to interact with the CLI. For example, for output to the
 * console.
 */
class Console
{
    private static $fgColors = array(
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37',
        'black_u' => '4;30',
        'red_u' => '4;31',
        'green_u' => '4;32',
        'yellow_u' => '4;33',
        'blue_u' => '4;34',
        'purple_u' => '4;35',
        'cyan_u' => '4;36',
        'white_u' => '4;37',
    );

    private static $bgColors = array(
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47',
    );

    /**
     * resets the setted colors of the console.
     */
    public static function resetColors()
    {
        echo "\033[0m";
    }

    /**
     * sets a new font-color or font-style, if it exists.
     *
     * @param $color
     */
    public static function setColor($color)
    {
        if (isset(self::$fgColors[$color])) {
            echo "\033[".self::$fgColors[$color].'m';
        }
    }

    /**
     * sets the background-color of the font, if it exists.
     *
     * @param $color
     */
    public static function setBgColor($color)
    {
        if (isset(self::$bgColors[$color])) {
            echo "\033[".self::$bgColors[$color].'m';
        }
    }

    /**
     * writes to the console
     * if $newLine is true, it will append a linebreak.
     *
     * @param $str
     * @param bool $newLine
     */
    public static function write($str, $newLine = false)
    {
        echo $str;
        echo $newLine ? "\n\r" : '';
    }

    /**
     * calls the write-method with $newLine = true.
     *
     * @param $str
     */
    public static function writeln($str)
    {
        self::write($str, true);
    }

    /**
     * allows to read the input of the command line.
     *
     * @return string
     */
    public static function read()
    {
        $stream = fopen('php://stdin', 'r');

        return fgets($stream);
    }

    /**
     * red colored writeln, for outputting errors.
     *
     * @param $str
     */
    public static function error($str)
    {
        self::setColor('red');
        self::writeln($str);
        self::resetColors();
    }

    /**
     * green colored writeln, for outputting successful messages.
     *
     * @param $str
     */
    public static function success($str)
    {
        self::setColor('green');
        self::writeln($str);
        self::resetColors();
    }
}
