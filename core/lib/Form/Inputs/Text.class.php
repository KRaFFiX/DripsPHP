<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:39.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Form\Form;
use DripsPHP\Form\Input;

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
        $name = $this->getRealName();
        $value = $this->getValue();
        if ($this->saveValue && array_key_exists($name, Form::$data)) {
            $values = Form::$data[$name];
            if (!is_array($values)) {
                $this->attributes['value'] = $values;
            } elseif (is_array($values) && isset($values[$this->index])) {
                $this->attributes['value'] = $values[$this->index];
            }
        }

        return '<input'.parent::__toString().'/>';
    }
}
