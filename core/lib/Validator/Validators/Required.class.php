<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.02.15 - 16:52.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Required implements IValidator
{
    /**
     * returns if string is not empty (using trim).
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        $str = trim($str);

        return !empty($str);
    }
}
