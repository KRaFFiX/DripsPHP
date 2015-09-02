<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:39.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Form\Form;
use DripsPHP\Form\Input;

/**
 * Class Textarea.
 *
 * represents a textarea
 */
class Textarea extends Input
{
    /**
     * used for print textarea.
     *
     * @return string
     */
    public function __toString()
    {
        $name = $this->getRealName();
        $value = $this->getValue();
        if($this->saveValue && array_key_exists($name, Form::$data)){
            $values = Form::$data[$name];
            if(!is_array($values)){
                $this->attributes["value"] = $values;
            } elseif(is_array($values) && isset($values[$this->index])) {
                $this->attributes["value"] = $values[$this->index];
            }
        }

        $value = isset($this->attributes['value']) ? $this->attributes['value'] : '';
        unset($this->attributes['value']);

        return '<textarea'.parent::__toString().'>'.$value.'</textarea>';
    }
}
