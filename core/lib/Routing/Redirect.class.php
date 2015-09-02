<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 31.01.15 - 16:43.
 */
namespace DripsPHP\Routing;

/**
 * Class Redirect.
 *
 * used for redirecting
 */
class Redirect
{
    /**
     * redirects to an $url.
     *
     * @param $url
     * @param bool $redirect
     *
     * @return string
     */
    public static function toURL($url, $redirect = true)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $url = Request::getDocRoot().$url;
        }
        if ($redirect) {
            if (!headers_sent()) {
                header("Location: $url");
                exit;
            } else {
                echo "<meta http-equiv='refresh' content='0, URL=$url'/>";
            }
        } else {
            return $url;
        }
    }

    /**
     * redirects to an specific route.
     *
     * @param $name
     * @param array $args
     * @param bool  $redirect
     *
     * @return string
     */
    public static function toRoute($name, $args = array(), $redirect = true)
    {
        $url = RequestHandler::getRouteByName($name);
        if ($url !== null) {
            foreach ($args as $key => $val) {
                $url = str_replace('{'.$key.'}', $val, $url);
            }
            $url = preg_replace('/\/\{\w{1,}\}/', '', $url);

            $docRoot = Request::getDocRoot();
            if($docRoot != "/"){
                return self::toURL("/".$docRoot.trim($url, "/"), $redirect);
            }
            return self::toURL($url, $redirect);
        }
    }

    /**
     * returns the url to a specific route.
     *
     * @param $name
     * @param array $args
     *
     * @return string
     */
    public static function link($name, $args = array())
    {
        return self::toRoute($name, $args, false);
    }

    /**
     * returns the full path to an asset.
     *
     * @param $name
     *
     * @return string
     */
    public static function asset($name, $package = "", $realPath = false)
    {
        if($package == "" && Request::$currentRoute !== null) {
            $callbacks = RequestHandler::getRoute(Request::$currentRoute)['route']->getCallbacks();
            $package = array_filter(explode('.', $callbacks[0]))[0];
        }

        if($realPath) {
            $url = 'src/'.$package.'/assets/'.$name;
        } else {
            $url = 'assets/'.$package.'/'.$name;
        }

        $docRoot = Request::getDocRoot();
        $url = $docRoot.$url;

        return $url;
    }

    /**
     * Reloads the current page
     */
    public static function reload()
    {
        Redirect::toURL($_SERVER["REQUEST_URI"]);
    }

}
