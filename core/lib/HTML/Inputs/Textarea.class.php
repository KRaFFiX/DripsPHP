<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:39.
 */
namespace DripsPHP\HTML\Inputs;

use DripsPHP\HTML\Form;
use DripsPHP\HTML\Input;

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
        if ($this->saveValue && array_key_exists($this->attributes['name'], Form::$data)) {
            $this->attributes['value'] = Form::$data[$this->attributes['name']];
        }
        $value = isset($this->attributes['value']) ? $this->attributes['value'] : '';
        unset($this->attributes['value']);

        return '<textarea'.parent::__toString().'>'.$value.'</textarea>';
    }
}
