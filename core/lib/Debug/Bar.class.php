<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 13.03.15 - 20:42.
 */
namespace DripsPHP\Debug;

/**
 * Class Bar.
 *
 * used for creating the DripsPHP DebugBar
 */
abstract class Bar
{
    /**
     * includes layout.php which contains the DebugBar.
     */
    public static function create()
    {
        include __DIR__.'/bar/layout.php';
    }
}
