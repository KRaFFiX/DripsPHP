<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 14.02.15 - 13:10.
 */
namespace DripsPHP\Cache;

use Closure;

interface ICache
{
    public function get(Closure $content);

    public function exists();

    public function put($content);

    public function clear();
}
