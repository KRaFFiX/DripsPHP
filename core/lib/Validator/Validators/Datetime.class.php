<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 31.03.15 - 20:13.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Datetime implements IValidator
{
    /**
     * returns if string with format yyyy-mm-dd hh:ii[:ss] is a valid datetime.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        $datetimeParts = explode(' ', $str);
        if (count($datetimeParts) == 2) {
            return Date::validate($datetimeParts[0]) && Time::validate($datetimeParts[1]);
        }

        return false;
    }
}
