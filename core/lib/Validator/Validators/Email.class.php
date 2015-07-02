<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.02.15 - 18:15.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Email implements IValidator
{
    /**
     * returns if string is a valid email address.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }
}
