<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.05.15 - 14:53.
 */
namespace DripsPHP\Security;

use DripsPHP\Database\ORM\Filter;
use DripsPHP\ClassLoader\Path;

/**
 * Class Auth.
 *
 * used for authentification
 */
class Auth
{
    protected static $entityContainer;
    protected static $authName = 'basic_dp_auth';
    protected static $rememberDuration = 315360000;
    protected static $data = array();
    protected static $httpsOnly = false;

    /**
     * returns the class of the entity container.
     *
     * @return string
     */
    public static function getEntityContainer()
    {
        $path = new Path(static::$entityContainer);

        return $path->getClass();
    }

    /**
     * returns the current user if logged in otherwise null.
     *
     * @return Entity|null
     */
    public static function getCurrentUser()
    {
        if (static::already()) {
            $container = static::$entityContainer;

            return $container::get($_SESSION[static::$authName]);
        }

        return;
    }

    /**
     * sets the entity container of the authentication.
     *
     * @param $container
     *
     * @return bool
     */
    public static function setEntityContainer($container)
    {
        if (Path::isValid($container)) {
            static::$entityContainer = $container;

            return true;
        }

        return false;
    }

    /**
     * returns if user is already authenticated.
     *
     * @return bool
     */
    public static function already()
    {
        if (isset($_SESSION[static::$authName])) {
            return true;
        }

        return static::cookieLogin();
    }

    /**
     * login the user with $data.
     * optional you can set $remember to true.
     *
     * @param $data
     * @param bool $remember
     *
     * @return bool
     */
    public static function login($data, $remember = false)
    {
        static::$data = $data;

        $result = static::check($data);
        if ($result !== false) {
            return static::loginAs($result, $remember);
        }

        return false;
    }

    /**
     * returns the entity if data is correct otherwise false.
     *
     * @param $data
     *
     * @return Entity|bool
     */
    public static function check($data)
    {
        $container = static::getEntityContainer();
        $filter = new Filter();
        $filter->where(array('AND' => $data));
        $filter->only(1);
        $results = $container::getAll($filter);
        if (!empty($results)) {
            return implode('', $results[0]->getPrimaryKey());
        }

        return false;
    }

    /**
     * logs the current user out.
     *
     * @return bool
     */
    public static function logout()
    {
        session_destroy();
        session_regenerate_id();
        if (isset($_COOKIE[static::$authName])) {
            foreach ($_COOKIE[static::$authName] as $key) {
                setcookie(static::$authName."[$key]", '', time() - 1, '/', $_SERVER['SERVER_NAME'], true, static::$httpsOnly);
            }
        }
        unset($_COOKIE[static::$authName]);

        return true;
    }

    /**
     * login the user using uid, optional you can set remember.
     * Be careful this function does not check if uid is valid.
     *
     * @param $uid
     * @param bool $remember
     *
     * @return bool
     */
    public static function loginAs($uid, $remember = false)
    {
        $_SESSION[static::$authName] = $uid;
        if ($remember && !empty(static::$data)) {
            foreach (static::$data as $key => $value) {
                setcookie(static::$authName."[$key]", $value, time() + static::$remember_duration, '/', $_SERVER['SERVER_NAME'], true, static::$httpsOnly);
                $_COOKIE[static::$authName][$key] = $value;
            }
        }

        return true;
    }

    /**
     * login via cookies if isset (remember).
     *
     * @return bool
     */
    public static function cookieLogin()
    {
        if (isset($_COOKIE[static::$authName])) {
            return static::login($_COOKIE[static::$authName], true);
        }

        return false;
    }
}
