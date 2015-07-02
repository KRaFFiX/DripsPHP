<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 26.02.15 - 15:46.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Range implements IValidator
{
    /**
     * returns if string is numeric and between min and max.
     *
     * @param $str
     * @param $min
     * @param $max
     *
     * @return bool
     */
    public static function validate($str, $min = 1, $max = 10)
    {
        return Min::validate($str, $min) && Max::validate($str, $max);
    }
}
