<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 16:46.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Form\Form;
use DripsPHP\Form\Input;

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
       $name = $this->getRealName();
       if($this->saveValue && array_key_exists($name, Form::$data)){
           $values = Form::$data[$name];
           if((!is_array($values) && $values == $this->getValue()) || (is_array($values) && in_array($this->getValue(), $values))) {
               $this->attributes['checked'] = 'checked';
           }
       }

        return '<input'.parent::__toString().'/>';
    }
}
