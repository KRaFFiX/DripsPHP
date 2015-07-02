<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 11:42.
 */
namespace DripsPHP\Routing;

use DripsPHP\MVC\MVCHandler;
use Exception;

/**
 * Class RequestHandler.
 *
 * The request handler is responsible for the routing
 */
abstract class RequestHandler
{
    protected static $routes = array();
    protected static $found = false;
    protected static $foundRoute;

    /**
     * add a route object to the RequestHandlers routing table.
     *
     * @param Route $route
     */
    public static function addRoute(Route $route)
    {
        self::$routes[$route->getName()] = $route;
    }

    /**
     * returns if the requested route does exist you can also check if an other
     * route is available by setting the $myroute parameter.
     *
     * @param $myroute = NULL
     *
     * @return bool
     */
    public static function foundRoute($myroute = null)
    {
        // if already found the route, don't search again
        if (self::$found) {
            return true;
        }
        // only url without query-parameters
        $url = explode('?', ($myroute != null) ? $myroute : Request::getURI());
        $url = $url[0];
        foreach (self::$routes as $route) {
            $match = false;
            $params = array();
            //if route has allowed domain
            if ($route->isAllowedHost()) {
                //if $url does not start with / let it start with /
                if (substr($url, 0, 1) != '/') {
                    $url = '/'.$url;
                }
                //if $routeUrl does not end with / let it end with /
                $routeUrl = $route->getURL();
                if (substr($routeUrl, strlen($routeUrl) - 1, 1) != '/') {
                    $routeUrl .= '/';
                }
                $match = true;
                $params = array();
                if ($url != $route->getURL() || !preg_match('/\{/', $url)) {
                    $match = false;
                    //split requested url
                    $requestParts = explode('/', $url);
                    //split url of route
                    $routeParts = explode('/', $routeUrl);
                    //does the route lengths match
                    if (count($routeParts) >= count($requestParts)) {
                        for ($i = 0; $i < count($requestParts); $i++) {
                            //if requested part != part of the route
                            if ($requestParts[$i] != $routeParts[$i]) {
                                if (preg_match('/\{\w{1,}\}/', $routeParts[$i])) {
                                    //save parameter
                                    $params[str_replace('{', '', str_replace('}', '', $routeParts[$i]))] = $requestParts[$i];
                                    $match = true;
                                } else {
                                    $match = false;
                                    break;
                                }
                            } else {
                                $match = true;
                            }
                        }
                    } else {
                        $match = false;
                    }
                }
            }
            if ($match) {
                self::$found = true;
                $result = array(
                    'name' => $route->getName(),
                    'route' => $route,
                    'params' => $params,
                );
                self::$foundRoute = $result;

                return true;
            }
        }

        return false;
    }

    /**
     * does the same than the foundRoute method, but it returns the route or NULL
     * (if not found).
     *
     * @return array|null
     */
    public static function getRoute($myroute = null)
    {
        if (self::foundRoute($myroute)) {
            return self::$foundRoute;
        }

        return;
    }

    /**
     * returns the url of the route by name.
     *
     * @param $name
     *
     * @return string
     *
     * @throws RequestHandlerRouteNotFoundException
     */
    public static function getRouteByName($name)
    {
        foreach (array_keys(self::$routes) as $routename) {
            if ($routename == $name) {
                return self::$routes[$name]->getURL();
            }
        }

        throw new RequestHandlerRouteNotFoundException($name);
    }

    /**
     * searches for routes.json files in src-directory packages and register
     * routes in the file to the RequestHandler.
     */
    protected static function registerRoutes($path = 'src/*/routes.json')
    {
        foreach (glob($path) as $jsonpath) {
            if (file_exists($jsonpath)) {
                $json = json_decode(file_get_contents($jsonpath), JSON_OBJECT_AS_ARRAY);
                foreach ($json as $name => $route) {
                    $routeObj = new Route($name, $route['url']);
                    if (isset($route['domain'])) {
                        if (is_array($route['domain'])) {
                            foreach ($route['domain'] as $domain) {
                                $routeObj->addDomain($domain);
                            }
                        } else {
                            $routeObj->addDomain($route['domain']);
                        }
                    }
                    if (isset($route['https'])) {
                        $routeObj->setHTTPS($route['https']);
                    }
                    if (isset($route['callback'])) {
                        $routeObj->addCallback($route['callback']);
                    }
                    self::addRoute($routeObj);
                }
            }
        }
    }

    /**
     * responsible for doing the routing.
     *
     * @throws Error404
     */
    public static function route()
    {
        self::registerRoutes();
        self::registerRoutes('plugins/*/routes.json');
        if (self::foundRoute()) {
            $route = self::getRoute();
            Request::$currentRoute = $route['route'];
            ob_start();
            foreach ($route['route']->getCallbacks() as $callback) {
                if (is_callable($callback)) {
                    echo call_user_func_array($callback, $route['params']);
                } else {
                    // If not callable, try to use the MVC-System
                    echo MVCHandler::request($callback, $route['params']);
                }
            }
            Response::$content = ob_get_contents();
            ob_end_clean();
            Response::send();
        } else {
            throw new Error404();
        }
    }
}

class Error404 extends Exception
{
}

class RequestHandlerRouteNotFoundException extends Exception
{
}
