<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 27.01.15 - 20:18.
 */
namespace DripsPHP\Debug;

/**
 * Class ErrorHandler.
 *
 * used for saving errors, for DripsPHP DebugPage
 */
abstract class ErrorHandler
{
    /**
     * saves the error.
     *
     * @param [type] $errno       [description]
     * @param [type] $errstr      [description]
     * @param [type] $errfile     [description]
     * @param [type] $errline     [description]
     * @param [type] $errcontext  [description]
     * @param bool   $isException [description]
     *
     * @return [type] [description]
     */
    public static function handle($errno, $errstr, $errfile, $errline, $errcontext, $isException = false)
    {
        $_ENV['DP_DEBUG'][] = array(
            'number' => $errno,
            'desc' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'context' => $errcontext,
            'isException' => $isException,
        );
    }
}
