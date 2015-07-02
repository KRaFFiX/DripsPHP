<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.02.15 - 10:34.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Url implements IValidator
{
    /**
     * returns if string is a valid url.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        return filter_var($str, FILTER_VALIDATE_URL);
    }
}
