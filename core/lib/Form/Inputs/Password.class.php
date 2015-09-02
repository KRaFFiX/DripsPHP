<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:39.
 */
namespace DripsPHP\Form\Inputs;

/**
 * Class Password.
 *
 * represents a password field
 */
class Password extends Text
{
    /**
     * uses an alternativ constructor which does not save the value automatically.
     *
     * @param $attributes
     * @param bool $saveValue
     */
    public function __construct($attributes, $saveValue = false)
    {
        parent::__construct($attributes, $saveValue);
    }

    /**
     * used for print passoword field.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'password';

        return parent::__toString();
    }
}
