<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.02.15 - 07:23.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Maxlength implements IValidator
{
    /**
     * returns if stringlength is lower than maxlength.
     *
     * @param $str
     * @param $maxlength
     *
     * @return bool
     */
    public static function validate($str, $maxlength = 15)
    {
        return strlen($str) <= $maxlength;
    }
}
