<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 16:52.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Validator\Validator;

/**
 * Class Number.
 *
 * represents input of type number
 */
class Number extends Text
{
    /**
     * used for print number field.
     * it also uses a number validator.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'number';
        $this->addValidator(new Validator('number'));

        return parent::__toString();
    }
}
