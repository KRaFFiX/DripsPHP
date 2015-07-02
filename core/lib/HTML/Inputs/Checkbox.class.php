<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 16:46.
 */
namespace DripsPHP\HTML\Inputs;

use DripsPHP\HTML\Form;
use DripsPHP\HTML\Input;

/**
 * Class Checkbox.
 *
 * Represents a checkbox as php object
 */
class Checkbox extends Input
{
    /**
     * used for print checkbox.
     *
     * @return string
     */
    public function __toString()
    {
        if (!isset($this->attributes['type'])) {
            $this->attributes['type'] = 'checkbox';
        }
        if (!array_key_exists('value', $this->attributes)) {
            $this->attributes['value'] = '';
        }
        if ($this->saveValue && array_key_exists($this->attributes['name'], Form::$data) && $this->attributes['value'] == Form::$data[$this->attributes['name']]) {
            $this->attributes['checked'] = 'checked';
        }

        return '<input'.parent::__toString().'/>';
    }
}
