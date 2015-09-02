<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 18:00.
 */
namespace DripsPHP\Form\Inputs;

use DripsPHP\Form\Form;
use DripsPHP\Form\Input;

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
            $name = $this->getRealName();
            $value = $option['value'];
            if (array_key_exists($name, Form::$data)) {
                $values = Form::$data[$name];
                if ((!is_array($values) && $values == $option['value']) || (is_array($values) && isset($values[$this->index]) && $values[$this->index] == $value)) {
                    $option['selected'] = 'selected';
                }
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
