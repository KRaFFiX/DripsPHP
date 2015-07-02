<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 29.03.15 - 21:34.
 */
namespace DripsPHP\Converter;

use Exception;

interface IConverter
{
    public static function convert($value, $fromUnit, $toUnit);
}

class UnitNotFoundException extends Exception
{
}
