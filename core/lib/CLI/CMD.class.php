<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.05.15 - 09:46.
 */
namespace DripsPHP\CLI;

use DripsPHP\Plugin\Registration;

/**
 * Class CMD.
 *
 * This class is used to store or register CMDs for DripsCLI.
 */
abstract class CMD extends Registration
{
    protected static $plugins = array();
}
