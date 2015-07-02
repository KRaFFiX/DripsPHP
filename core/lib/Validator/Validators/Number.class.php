<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 26.02.15 - 15:26.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Number implements IValidator
{
    /**
     * returns if string is numeric.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        return is_numeric($str);
    }
}
