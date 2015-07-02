<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 10:28.
 */
namespace DripsPHP\HTML;

use DripsPHP\Database\ORM\Entity;
use Exception;
use DripsPHP\Security\Hash;

/**
 * Class Form.
 *
 * used for creating forms
 */
class Form
{
    protected $name;
    protected $action;
    protected $method;
    protected $enctype;
    protected $token;
    public static $data = array();
    protected $elements = array();
    protected $entity;
    protected $entity_elements = array();
    protected $entity_results = array();

    /**
     * creates a new form object.
     *
     * @param $name
     * @param string $action
     * @param string $method
     * @param null   $enctype
     *
     * @throws FormMethodDoesNotExistException
     */
    public function __construct($name, $action = '', $method = 'POST', $enctype = null)
    {
        $this->name = $name;
        $this->action = $action;
        $method = strtoupper($method);
        if (!in_array($method, array('GET', 'POST'))) {
            throw new FormMethodDoesNotExistException();
        }
        $this->method = $method;
        $this->enctype = $enctype;
        if ($this->enctype == true) {
            $this->enctype = 'multipart/form-data';
        }
    }

    /**
     * uses an entity-object for creating a form used for changing entity data by
     * form.
     * You can bind an input field to an entity attribute by using the attributes
     * array. You need to specify key, which needs to be the name of input field
     * and the value of the array which is the name of the entity attribute.
     *
     * @param $entity
     * @param $attributes
     *
     * @return bool
     */
    public function from(Entity $entity, $attributes)
    {
        $this->entity = $entity;
        if (is_array($attributes)) {
            $this->entity_elements = $attributes;

            return true;
        }

        return false;
    }

    /**
     * get the new or changed entity.
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * adds an input element to the form.
     *
     * @param Input $element
     *
     * @return Input
     */
    public function add(Input $element)
    {
        $value = $element->getValue();
        if (empty($value) && !empty($this->entity_elements)) {
            $property = array_search($element->getName(), $this->entity_elements);
            if ($property !== false) {
                $method = 'get'.ucfirst($property);
                $element->setValue($this->entity->$method());
            }
        }
        $this->elements[$element->getName()] = $element;

        return $element;
    }

    /**
     * returns the input element named $name, if it does not exist it will return
     * null.
     *
     * @param $name
     *
     * @return Input|null
     */
    public function getInput($name)
    {
        if (array_key_exists($name, $this->elements)) {
            return $this->elements[$name];
        }

        return;
    }

    /**
     * returns all Input elements of the object.
     *
     * @return array
     */
    public function getInputs()
    {
        return $this->elements;
    }

    /**
     * returns form open tag as string (html).
     *
     * @return string
     */
    public function open()
    {
        $enctype = ($this->enctype != null) ? "enctype='".$this->enctype."'" : '';
        $this->token = Hash::random();

        return "<form name='".$this->name."' action='".$this->action."' method='".$this->method."' $enctype>".
        "<input type='hidden' name='dp-form-name' value='".$this->name."'/><input type='hidden' name='dp-form-token' value='".$this->token."'/>";
    }

    /**
     * returns form closing tag as string (html).
     *
     * @return string
     */
    public function close()
    {
        $this->reset();
        $_SESSION['DP_FORMS'][$this->name] = serialize($this);

        return '</form>';
    }

    /**
     * returns an existing form-object by name.
     * if form object was not found an empty form object will be returned.
     *
     * @param $name
     *
     * @return Form|mixed
     */
    public static function get($name)
    {
        if (!isset($_SESSION['DP_FORMS'][$name])) {
            //throw new FormNotFoundException($name);
            return new self('EMPTY');
        }

        $formObj = unserialize($_SESSION['DP_FORMS'][$name]);
        unset($_SESSION['DP_FORMS'][$name]);

        return $formObj;
    }

    /**
     * returns if the form has been submitted.
     *
     * @return bool
     */
    public function submitted()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == $this->method) {
            self::$data = $_GET;
            if ($this->method == 'POST') {
                self::$data = $_POST;
            }
            if ($this->token == self::$data['dp-form-token']) {
                return self::$data['dp-form-name'] == $this->name;
            } else {
                $this->reset();
            }
        }

        return false;
    }

    /**
     * returns if the form data is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        foreach($this->elements as $element){
            $results = $this->getValidatorResults($element->getName());
            foreach($results as $result){
                if(!$result){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * returns the value of an input element.
     *
     * @param $name
     *
     * @return string
     */
    public function value($name)
    {
        if (array_key_exists($name, $this->elements)) {
            if ($this->method == 'POST') {
                return $_POST[$name];
            }

            return $_GET[$name];
        }

        return '';
    }

    /**
     * converts input elements to an array (to receive namespace).
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->elements as $element) {
            $array[$element->getName()] = $element->__toString();
        }

        return $array;
    }

    /**
     * returns the name of the form.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * resets the form data.
     */
    public function reset()
    {
        self::$data = array();
    }

    /**
     * returns the results of the validators of an specific input element named
     * $name.
     *
     * @param $name
     *
     * @return array
     */
    public function getValidatorResults($name)
    {
        $input = $this->getInput($name);
        $validators = $input->getValidators();
        $results = array();
        foreach ($validators as $validator) {
            $result = $validator->getResults();
            $results = array_merge($results, $result);
        }

        if(isset($this->entity)){
            if(array_key_exists($name, $this->elements)){
                $element = $this->elements[$name];
                $name = $element->getName();
                if (array_key_exists($name, $this->entity_elements)) {
                    $property = $this->entity_elements[$name];
                    $value = $this->value($name);
                    $method = 'set'.ucfirst($property);
                    $this->entity_results[$property] = $this->entity->$method($value);
                }
            }
            $results = array_merge($results, $this->entity_results);
        }

        return $results;
    }
}

class FormMethodDoesNotExistException extends Exception
{
}

class FormNotFoundException extends Exception
{
}
