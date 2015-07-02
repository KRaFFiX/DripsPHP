<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.05.15 - 10:24.
 */
namespace DripsPHP\MVC;

use DripsPHP\Plugin\Registration;

/**
 * Class ViewPlugin.
 *
 * used for register view plugins
 */
abstract class ViewPlugin extends Registration
{
    protected static $plugins = array();
}
