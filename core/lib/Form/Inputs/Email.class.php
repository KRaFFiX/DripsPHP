<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:02.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Validator\Validator;

/**
 * Class Email.
 *
 * input email field like text, but also uses an email validator
 */
class Email extends Text
{
    /**
     * used for print Email field.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'email';
        $this->addValidator(new Validator('email'));

        return parent::__toString();
    }
}
