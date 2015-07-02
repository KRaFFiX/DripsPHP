<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 11:09.
 */
namespace DripsPHP\MVC;

use Exception;

/**
 * Class Controller.
 *
 * controller of mvc pattern
 */
abstract class Controller
{
    protected $view;

    /**
     * creates a new controller object and executes the init-method.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->init();
    }

    /**
     * requests an method of the controller with parameters.
     *
     * @param $method
     * @param array $params
     *
     * @return mixed
     *
     * @throws ControllerMethodNotFoundException
     */
    public function request($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        throw new ControllerMethodNotFoundException();
    }

    /**
     * overwriteable constructor.
     */
    public function init()
    {
        // You can overwrite this method in your controller
        // this method will be executed when the controller
        // will be created
    }
}

class ControllerMethodNotFoundException extends Exception
{
}
