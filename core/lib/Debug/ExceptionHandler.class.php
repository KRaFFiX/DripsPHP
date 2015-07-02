<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 11:45.
 */
namespace DripsPHP\Debug;

/**
 * Class ExceptionHandler.
 *
 * used for saving exception, for DripsPHP DebugPage
 */
abstract class ExceptionHandler
{
    /**
     * saves the exception.
     *
     * @param $exception
     */
    public static function handle($exception)
    {
        // convert exception to error and handle it like an error
        ErrorHandler::handle($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), serialize($exception), true);
    }
}
