<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 13:06.
 */
namespace DripsPHP\MVC;

interface IViewPlugin
{
    public function compile($template, $name);
}
