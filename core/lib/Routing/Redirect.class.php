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
}
