<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 10:17.
 */
namespace DripsPHP\Routing;

/**
 * Class RequestHandler.
 *
 * The request handler creates the necessary requirements for routing. Use the
 * Request Handler routing with different domains is also possible, even if the
 * domain is not pointing to the directory of the framework, but to a parent
 * directory.
 */
abstract class Request
{
    private static $docRoot;
    private static $requestURI;
    public static $currentRoute;

    /**
     * This method returns the DocumentRoot, starting from the current domain,
     * so how this information is given also via html. To put it another way:
     * the relative path of the domain to the framework directory.
     *
     * @return string
     */
    public static function getDocRoot()
    {
        if (!isset(self::$docRoot)) {
            self::$docRoot = substr(self::getCurrentPath(), strlen($_SERVER['DOCUMENT_ROOT'])).'/';
        }

        return self::$docRoot;
    }

    /**
     * Returns the requested URL. It is not the function called by the server
     * $_SERVER['REQUEST_URI'] but is returned but, that was the path specified
     * by the DocumentRoot.
     * This is the basis for routing.
     *
     * @return string
     */
    public static function getURI()
    {
        if (!isset(self::$requestURI)) {
            self::$requestURI = substr($_SERVER['REQUEST_URI'], strlen(self::getDocRoot())).'/';
        }

        return self::$requestURI;
    }

    /**
     * Returns the current directory path, that is, the path by the index.php of
     * the framework.
     *
     * @return string
     */
    public static function getCurrentPath()
    {
        return dirname($_SERVER['SCRIPT_FILENAME']);
    }

    /**
     * Returns if $routename is the current route.
     *
     * @param $routename
     *
     * @return bool
     */
    public static function current($routename)
    {
        return isset(self::$currentRoute) && self::$currentRoute->getName() == $routename;
    }

    /**
     * does exactly the same then current, but you can use an array for check
     *
     * @param array $routenames
     *
     * @return bool
     */
    public static function current_in(array $routenames)
    {
        foreach($routenames as $name){
            if(self::current($name)){
                return true;
            }
        }
        return false;
    }
}
