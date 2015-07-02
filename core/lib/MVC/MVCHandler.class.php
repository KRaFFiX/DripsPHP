<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 11:24.
 */
namespace DripsPHP\MVC;

use DripsPHP\App;
use DripsPHP\Config\Config;
use DripsPHP\Language\Detector;
use DripsPHP\Language\Dictionary;
use DripsPHP\ClassLoader\Path;
use Exception;

/**
 * Class MVCHandler.
 *
 * manages the MVC system of DripsPHP
 */
abstract class MVCHandler
{
    /**
     * splits drips notation in parts.
     *
     * @param $pcm
     *
     * @return array
     */
    public static function resolve($pcm)
    {
        $path = new Path($pcm);
        $result['controller'] = $path->getClassName();
        $result['classname'] = $path->getClass();
        if ($path->hasMethod()) {
            $result['method'] = $path->getMethod();
        } else {
            $result['method'] = $_SERVER['REQUEST_METHOD'];
        }

        $parts = explode('.', $pcm);
        if (count($parts) > 2 && $parts[0] == 'plugins') {
            $result['package'] = $parts[1];
        } else {
            $result['package'] = $parts[0];
        }

        return $result;
    }

    /**
     * requests the given controller method from pcm with parameters (optional).
     *
     * @param $pcm
     * @param array $params
     *
     * @return mixed
     *
     * @throws ControllerDoesNotExistException
     */
    public static function request($pcm, $params = array())
    {
        $pcm = self::resolve($pcm);
        if (class_exists($pcm['classname'])) {
            self::initLanguage($pcm['package']);
            $controller = new $pcm['classname']();
            if ($controller instanceof Controller) {
                return $controller->request($pcm['method'], $params);
            }
        }
        throw new ControllerDoesNotExistException($pcm['classname']);
    }

    /**
     * initializes language detection for $package.
     *
     * @param $package
     */
    public static function initLanguage($package)
    {
        $default_language = Config::get('lang-default');
        $langdirs = array("src/$package/langs", "plugins/$package/langs");
        Detector::setDefaultLanguage($default_language);
        // is a multilang-package?
        foreach ($langdirs as $langdir) {
            if (is_dir($langdir)) {
                if (is_readable($langdir.'/'.strtolower($default_language).'.json')) {
                    App::$dictionary = new Dictionary($package, $default_language);
                    $lang_packages = glob($langdir.'/*.json');
                    if (count($lang_packages) > 1) {
                        $supported_langs = array();
                        foreach ($lang_packages as $lang_package) {
                            $supported_langs[] = pathinfo($lang_package)['filename'];
                        }
                        Detector::setSupportedLanguages($supported_langs);
                        $current_language = Detector::getCurrentLanguage();
                        App::$dictionary->load($current_language);
                    }
                }
            }
        }
    }
}

class ControllerDoesNotExistException extends Exception
{
}
