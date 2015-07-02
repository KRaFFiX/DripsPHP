<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.02.15 - 10:37.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Minlength implements IValidator
{
    /**
     * returns if the string length is greater than minlength.
     *
     * @param $str
     * @param $minlength
     *
     * @return bool
     */
    public static function validate($str, $minlength = 3)
    {
        return strlen($str) >= $minlength;
    }
}
