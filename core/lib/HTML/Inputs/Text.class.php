<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:39.
 */
namespace DripsPHP\HTML\Inputs;

use DripsPHP\HTML\Form;
use DripsPHP\HTML\Input;

/**
 * Class Text.
 *
 * represents a normal text field
 */
class Text extends Input
{
    /**
     * used for print the text field.
     *
     * @return string
     */
    public function __toString()
    {
        if (!isset($this->attributes['type'])) {
            $this->attributes['type'] = 'text';
        }
        if ($this->saveValue && array_key_exists($this->attributes['name'], Form::$data)) {
            $this->attributes['value'] = Form::$data[$this->attributes['name']];
        }

        return '<input'.parent::__toString().'/>';
    }
}
