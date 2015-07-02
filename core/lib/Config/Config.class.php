<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 03.02.15 - 12:34.
 */
namespace DripsPHP\Config;

/**
 * Class Config.
 *
 * used for the DripsPHP configuration
 */
abstract class Config extends Configuration
{
    public static $env = 'dev';

    /**
     * loads the config from core/config via ConfigLoader.
     */
    public static function init()
    {
        $cl = new ConfigLoader();
        if ($cl->load(self::$env)) {
            self::$config = $cl->getConfig();
        }
    }
}
