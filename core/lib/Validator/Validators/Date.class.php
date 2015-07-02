<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 31.03.15 - 20:13.
 */
namespace DripsPHP\Validator\Validators;

use DripsPHP\Validator\IValidator;

class Date implements IValidator
{
    /**
     * returns if string with format yyyy-mm-dd is a valid date.
     *
     * @param $str
     *
     * @return bool
     */
    public static function validate($str)
    {
        // format: yyyy-mm-dd
        $dateParts = explode('-', $str);
        if (count($dateParts) == 3) {
            return checkdate($dateParts[1], $dateParts[2], $dateParts[0]);
        }

        return false;
    }
}
