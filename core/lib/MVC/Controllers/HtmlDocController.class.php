<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 13:33.
 */
namespace DripsPHP\MVC\Controllers;

use DripsPHP\HTML\HTMLDocument;
use DripsPHP\MVC\Controller;

/**
 * Class HtmlDocController.
 *
 * contains a HTMLDocument object
 */
abstract class HtmlDocController extends Controller
{
    protected $htmldoc;

    /**
     *  start creating a new HTMLDocument.
     */
    public function __construct()
    {
        $this->htmldoc = new HTMLDocument();
        parent::__construct();
    }

    /**
     * automatically set body of the document
     * and convert HTMLDocument to String.
     *
     * @param $method
     * @param array $params
     *
     * @return mixed
     *
     * @throws \DripsPHP\MVC\ControllerMethodNotFoundException
     */
    public function request($method, $params = array())
    {
        ob_start();
        echo parent::request($method, $params);
        $request = ob_get_contents();
        ob_end_clean();
        $this->htmldoc->setBody($request);

        return $this->htmldoc->__toString();
    }
}
