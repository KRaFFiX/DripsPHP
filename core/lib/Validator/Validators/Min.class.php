<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 26.02.15 - 15:45.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Min implements IValidator
{
    /**
     * returns if string is numeric and greater than min.
     *
     * @param $str
     * @param $min
     *
     * @return bool
     */
    public static function validate($str, $min = 1)
    {
        if (Number::validate($str)) {
            return $str >= $min;
        }

        return false;
    }
}
