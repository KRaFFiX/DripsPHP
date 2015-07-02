<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 12:08.
 */
namespace DripsPHP\Routing;

/**
 * Class Response.
 *
 * represents a http-response
 */
abstract class Response
{
    public static $type = 'text/html';
    public static $cache = 'max-age=0';
    public static $content = '';
    public static $statusCode = '200';

    /**
     * sends the response to the client sets/manipulates the header show content.
     *
     * @param null $content
     */
    public static function send($content = null)
    {
        header('Content-Type: '.self::$type);
        header('Cache-Control: '.self::$cache);
        http_response_code(self::$statusCode);
        echo($content !== null) ? $content : self::$content;
    }
}
