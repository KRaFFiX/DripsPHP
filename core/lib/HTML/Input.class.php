<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:40.
 */
namespace DripsPHP\HTML;

use DripsPHP\Validator\Validator;

/**
 * Class Input.
 *
 * used for creating input-elements
 */
abstract class Input
{
    protected $attributes = array();
    protected $saveValue;
    protected $validators = array();

    /**
     * creates a new input element.
     *
     * @param $attributes
     * @param bool $saveValue
     */
    public function __construct($attributes, $saveValue = true)
    {
        if (is_array($attributes)) {
            $this->attributes = $attributes;
        } else {
            $this->attributes['name'] = $attributes;
        }
        $this->saveValue = $saveValue;
    }

    /**
     * returns the value of the input-element.
     *
     * @return string
     */
    public function getValue()
    {
        if (array_key_exists('value', $this->attributes)) {
            return $this->attributes['value'];
        }

        return '';
    }

    /**
     * sets the value of the input-element.
     *
     * @param $value
     */
    public function setValue($value)
    {
        $this->attributes['value'] = $value;
    }

    /**
     * returns the name of the input-element.
     *
     * @return string
     */
    public function getName()
    {
        return $this->attributes['name'];
    }

    /**
     * adds an validator to the input-element.
     *
     * @param Validator $validator
     *
     * @return $this
     */
    public function addValidator(Validator $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * returns if the input is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->validators as $validator) {
            $validator->set(Form::$data[$this->getName()]);
            if (!$validator->validate()) {
                return false;
            }
        }

        return true;
    }

    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * converts input-element to html-tag.
     *
     * @return string
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->attributes as $key => $value) {
            if ($key == 'required') {
                $this->addValidator(new Validator('required'));
            }
            if ($key == 'maxlength') {
                $this->addValidator(new Validator('maxlength'));
            }
            if ($key == 'pattern') {
                $this->addValidator(new Validator(['regex' => $value]));
            }
            if ($key == 'min') {
                $this->addValidator(new Validator(['min' => $value]));
            }
            if ($key == 'max') {
                $this->addValidator(new Validator(['max' => $value]));
            }
            $str .= " $key='$value'";
        }

        return $str;
    }
}
