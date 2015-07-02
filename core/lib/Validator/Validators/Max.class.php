<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 26.02.15 - 15:45.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Max implements IValidator
{
    /**
     * returns if string is numeric and lower than max.
     *
     * @param $str
     * @param $max
     *
     * @return bool
     */
    public static function validate($str, $max = 10)
    {
        if (Number::validate($str)) {
            return $str <= $max;
        }

        return false;
    }
}
