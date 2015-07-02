<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 11:25.
 */
namespace DripsPHP\Routing;

/**
 * Class Route.
 *
 * Represents a route as an object
 */
class Route
{
    protected $name;
    protected $url;
    protected $https;
    protected $domains = array();
    protected $callbacks = array();

    /**
     * Define necessary properties of the route and generate an object.
     *
     * @param $name
     * @param $url
     * @param bool $https
     */
    public function __construct($name, $url, $https = false)
    {
        $this->setName($name);
        $this->setURL($url);
        $this->setHTTPS($https);
    }

    /**
     * add a callback method to the route, which would be executed if the route
     * has been requested.
     *
     * @param $callback
     */
    public function addCallback($callback)
    {
        $this->callbacks[] = $callback;
    }

    /**
     * add a domain to the route.
     * so you only can select the route if you are using the right domain.
     *
     * @param $domain
     */
    public function addDomain($domain)
    {
        $this->domains[] = $domain;
    }

    /**
     * set or change the name of the route.
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * set or change the url of the route.
     *
     * @param $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * define if route is only available if using https or not.
     *
     * @param bool $https
     */
    public function setHTTPS($https = true)
    {
        $this->https = ($https === true);
    }

    /**
     * returns the current name of the route.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * returns the current url of the route.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * returns if you must use https for requesting the route.
     *
     * @return bool
     */
    public function isHTTPS()
    {
        return $this->https;
    }

    /**
     * returns the allowed domains as array
     * if there are no restrictions it will return an empty array.
     *
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * returns all registered callback methods as array
     * if there are no registered callback methods it will return an
     * empty array.
     *
     * @return array
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * converts the route to an string
     * this should/is only be used for debugging.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name.': '.$this->url;
    }

    /**
     * check if the current route is available for the current domain
     * and if you need to use https or not.
     *
     * @return bool
     */
    public function isAllowedHost()
    {
        $host = $_SERVER['HTTP_HOST'];
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
        if ($isHttps === $this->isHTTPS()) {
            return in_array($host, $this->domains) || empty($this->domains);
        }

        return false;
    }

    /**
     *  adds the route to the RequestHandler.
     */
    public function register()
    {
        RequestHandler::addRoute($this);
    }
}
