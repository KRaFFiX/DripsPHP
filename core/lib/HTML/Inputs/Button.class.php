<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 18:46.
 */
namespace DripsPHP\HTML\Inputs;

use DripsPHP\HTML\Input;

/**
 * Class Button.
 *
 * represents an button as php object
 */
class Button extends Input
{
    /**
     * used for print the button.
     *
     * @return string
     */
    public function __toString()
    {
        if (!isset($this->attributes['type'])) {
            $this->attributes['type'] = 'submit';
        }
        $value = isset($this->attributes['value']) ? $this->attributes['value'] : '';
        unset($this->attributes['value']);

        return '<button'.parent::__toString().'>'.$value.'</button>';
    }
}
