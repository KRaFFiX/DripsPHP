<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:40.
 */
namespace DripsPHP\Form;

use DripsPHP\Validator\Validator;
use DripsPHP\Validator\Validators\Required;

/**
 * Class Input.
 *
 * used for creating input-elements
 */
abstract class Input {
    protected $attributes = array();
    protected $isOptional = false;
    protected $saveValue;
    protected $validators = array();
    protected $noHTML = true;
    protected static $names = array();
    protected $index = null;

    /**
     * creates a new input element.
     *
     * @param $attributes
     * @param bool $saveValue
     */
    public function __construct($attributes, $saveValue = true) {
        if (is_array($attributes)) {
            $this->setAttributes($attributes);
        } else {
            $this->setAttribute('name', $attributes);
        }
        $this->saveValue = $saveValue;
        $name = $this->getName();
        $index = 0;
        if(array_key_exists($name, self::$names)){
            $index = self::$names[$name] + 1;
        }
        self::$names[$name] = $index;
        $this->index = $index;

    }

    /**
     * returns the value of the input-element.
     *
     * @return string
     */
    public function getValue() {
        if (array_key_exists('value', $this->attributes)) {
            $value = $this->attributes["value"];
            if ($this->noHTML) {
                $value = strip_tags($value);
            }
            return $value;
        }

        return '';
    }

    /**
     * sets the value of the input-element.
     *
     * @param $value
     */
    public function setValue($value) {
        $this->attributes['value'] = $value;
    }

    /**
     * returns the name of the input-element.
     *
     * @return string
     */
    public function getName() {
        if(isset($this->attributes['name'])){
            return $this->attributes['name'];
        }
        return;
    }

    /**
     * returns the real name of the input-element, which means if you specify
     * a name which is an array f.e. files[] you will not receive files[] but
     * files
     *
     * @return string
     */
    public function getRealName()
    {
        $name = $this->getName();
        $match = array();
        if(preg_match("/^(.*)\[\]$/", $name, $match)){
            $name = $match[1];
        }
        return $name;
    }

    /**
     * sets the specified attribute to the specified value
     *
     * @param $attribute
     * @param $value
     */
    public function setAttribute($attribute, $value)
    {
        if($attribute == "optional"){
            $this->isOptional = true;
        } else {
            $this->attributes[$attribute] = $value;
        }
    }

    /**
     * sets the specified attribute to the specified value
     *
     * @param $attribute
     * @param $value
     */
    public function setAttributes(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * returns the value of the specified attribute
     *
     * @param $attribute
     * @return mixed
     */
    public function getAttribute($attribute) {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }
        return null;
    }

    /**
     * adds an validator to the input-element.
     *
     * @param Validator $validator
     *
     * @return $this
     */
    public function addValidator(Validator $validator) {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * returns if the input is valid.
     *
     * @return bool
     */
    public function isValid() {
        $value = "";
        if(array_key_exists($this->getName(), Form::$data)){
            $value = Form::$data[$this->getName()];
        }
        if($this->isOptional){
            if(!Required::validate($value)){
                return true;
            }
        } else {
            $this->addValidator(new Validator("required"));
        }
        foreach ($this->validators as $validator) {
            $validator->set($value);
            if (!$validator->validate()) {
                return false;
            }
        }

        return true;
    }

    public function getValidators() {
        return $this->validators;
    }

    /**
     * converts input-element to html-tag.
     *
     * @return string
     */
    public function __toString() {
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
            if ($key == "value") {
                $value = $this->getValue();
            }
            $str .= " $key='$value'";
        }

        return $str;
    }

    public function allowHTML($allow = true) {
        $this->noHTML = !$allow;
    }
}
