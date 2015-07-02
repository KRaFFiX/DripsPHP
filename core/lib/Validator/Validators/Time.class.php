<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 31.03.15 - 20:13.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Time implements IValidator
{
    /**
     * returns if string with format hh:ii[:ss] is a valid time.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        $regex = '/^([0-9]|0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){1,2}$/';

        return Regex::validate($str, $regex);
    }
}
