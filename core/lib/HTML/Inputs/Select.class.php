<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 18:00.
 */
namespace DripsPHP\HTML\Inputs;

use DripsPHP\HTML\Form;
use DripsPHP\HTML\Input;

/**
 * Class Select.
 *
 * represents select field
 */
class Select extends Input
{
    protected $options = array();

    /**
     * used for print select box.
     *
     * @return string
     */
    public function __toString()
    {
        $this->options = $this->attributes['options'];
        unset($this->attributes['options']);

        return '<select'.parent::__toString().'>'.$this->renderOptions().'</select>';
    }

    /**
     * convert options to string for generating select box.
     *
     * @return string
     */
    protected function renderOptions()
    {
        $str = '';
        foreach ($this->options as $option) {
            if (array_key_exists($this->attributes['name'], Form::$data) && $option['value'] == Form::$data[$this->attributes['name']]) {
                $option['selected'] = 'selected';
            }
            $text = $option['value'];
            if (isset($option['text'])) {
                $text = $option['text'];
                unset($option['text']);
            }
            $optionsStr = '';
            foreach ($option as $key => $value) {
                $optionsStr .= " $key='$value'";
            }
            $str .= '<option'.$optionsStr.">$text</option>";
        }

        return $str;
    }
}
