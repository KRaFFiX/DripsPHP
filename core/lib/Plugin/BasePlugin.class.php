<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.05.15 - 15:52.
 */
namespace DripsPHP\Plugin;

/**
 * Class BasePlugin.
 *
 * template for creating plugins
 */
abstract class BasePlugin implements IPlugin
{
    const AUTHOR = '';
    const NAME = '';
    const DESC = '';
    const VERSION = '';

    public function __construct()
    {
    }
}
