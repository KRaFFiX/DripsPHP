<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 30.06.15 - 9:10.
 */
namespace DripsPHP\MVC\Controllers;

use DripsPHP\MVC\Controller;
use DripsPHP\Config\Config;
use DripsPHP\Language\Detector;
use DripsPHP\Routing\Redirect;

class LanguageController extends Controller
{
    public function get($lang = null)
    {
        if($lang === null){
            $lang = Config::get("lang-default");
        }
        Detector::setCookieLanguage($lang);
        Redirect::toURL($_SERVER["HTTP_REFERER"]);
    }
}
