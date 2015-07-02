<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.02.15 - 10:27.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Regex implements IValidator
{
    /**
     * validates the string with regex.
     *
     * @param $str
     * @param $regex
     *
     * @return bool
     */
    public static function validate($str, $regex = null)
    {
        if ($regex !== null) {
            return preg_match($regex, $str);
        }

        return false;
    }
}
