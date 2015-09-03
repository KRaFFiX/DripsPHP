<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 28.08.15 - 15:30.
 */
namespace DripsPHP\Form;

use DripsPHP\Security\Hash;
use DripsPHP\Form\Input;
use DripsPHP\API\Dispatcher;
use DripsPHP\Database\ORM\Entity;

/**
 * Class Form.
 *
 * used for creating forms
 */
abstract class Form extends Dispatcher
{
    private $token;
    private $inputs = array();

    public static $data = array();
    protected $entities = array();

    private $action = "";
    private $enctype = "";
    private $method = "POST";

    private $finished = false;
    protected $multiple_errors = false;

    /**
     * creates a new form instance.
     * this will automatically execute all definied functions like init, render,
     * submit, ...
     */
    public function __construct()
    {
        $name = $this->getName();

        if (isset($_SESSION['DP_FORMS'][$name])) {
            $formObj = unserialize($_SESSION['DP_FORMS'][$name]);
            $token = $formObj->getToken();
            unset($_SESSION['DP_FORMS'][$name]);
            $this->token = $token;
        }
        $this->init();

        if(!isset($_SERVER["REQUEST_METHOD"]) || strtoupper($_SERVER["REQUEST_METHOD"]) == $this->method){
            self::$data = $_GET;
            if($this->method == "POST"){
                self::$data = $_POST;
            }
        }
        if($this->submitted()){
            if($this->isValid()){
                $this->finished = $this->submit();
            }
        }

    }

    /**
     * use this function as constructor for initializing your form
     */
    public function init()
    {
        // INITIALIZE YOUR FORM
    }

    /**
     * use this function for rendering your form
     */
    public function render()
    {
        // YOUR FORM OUTPUT
    }

    /**
     * this function will be executed if form was submitted an is valid.
     * it is important to return true or false - if this function was successful
     *
     * @return bool
     */
    public function submit()
    {
        // YOUR SUBMIT ACTION (return true/false)
        return true;
    }

    /**
     * use this function for your manual validation.
     * it is important to return true or false - if this function was successful
     * @return bool
     */
    public function validate()
    {
        // VALIDATE YOUR FORM (events on error - return true/false)
        return true;
    }

    /**
     * this function automatically renders your form and returns it as string
     *
     * @return string
     */
    public function __toString()
    {
        ob_start();
        echo $this->render();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * returns if the current form was submitted
     *
     * @return bool
     */
    public function submitted()
    {
        if(array_key_exists("dp-form-token", self::$data) && array_key_exists("dp-form-name", self::$data)){
            if($this->getToken() == self::$data["dp-form-token"] && $this->getName() == self::$data["dp-form-name"]){
                return true;
            }
            $this->reset();
        }
        return false;
    }

    /**
     * returns if the current form is valid, which means that this function
     * does automatically validate input values and validates with your validate
     * method
     *
     * @return bool
     */
    public function isValid()
    {
        $valid = true;
        foreach($this->inputs as $name => $input){
            $results = $this->getValidatorResults($name);
            foreach($results as $result){
                if(!$result){
                    static::call("invalid", array($name, $input));
                    $valid = false;
                    if($this->multiple_errors == false){
                        return false;
                    }
                    break;
                }
            }
        }
        if($valid){
            return $this->validate();
        }
        return false;
    }

    /**
     * this function returns als validator results, which means all validators
     * of an specific input element named $name will be checked an returned.
     * if you have bound an entity to that input field it will automatically check
     * if the current value is valid for the entity
     *
     * @param $name
     *
     * @return array
     */
    public function getValidatorResults($name)
    {
        $results = array();
        if($this->has($name)){
            $input = $this->get($name);
            if(!$input->isValid()){
                $validators = $input->getValidators();
                foreach($validators as $validator){
                    $results = array_merge($results, $validator->getResults());
                }
            }

            if(!empty($this->entities)){
                foreach($this->entities as $entity){
                    $attributes = $entity["attributes"];
                    $entityObj = $entity["entity"];
                    if(array_key_exists($name, $attributes)){
                        $property = $attributes[$name];
                        $value = $this->getValue($name);
                        $method = 'set'.ucfirst($property);
                        $result = $entityObj->$method($value);
                        $results[] = $result;
                    }
                }
            }
        }
        return $results;
    }

    /**
     * returns if form is submitted, is valid and the submit-action was successful
     *
     * @return bool
     */
    public function succeeded()
    {
        return $this->finished;
    }

    /**
     * You can bind an entity to an input field if your use this bind function.
     * For that you need to specify an entity and also an array which defines,
     * which attributes of the entities can be found in which input-fields.
     * That means you need to connect input-field with entity attribute.
     *
     * @param Entity $entity
     * @param array $attributes
     */
    public function bind(Entity $entity, array $attributes)
    {
        $this->entities[] = array("entity" => $entity, "attributes" => $attributes);
    }

    /**
     * returns all input-elements
     *
     * @return array
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * returns "changed" entities.
     * if you have bound an entity to the form you can receive the changed Entity
     * from here.
     * if you only bound one entity to this form, you will receive only the entity
     * object otherwise, if you have bound multiple entities this function will
     * return an array with all entities, you have bound to the form.
     *
     * @return mixed
     */
    public function getEntities()
    {
        if(count($this->entities) == 1){
            return $this->entities[0]["entity"];
        }
        $entities = array();
        foreach($this->entities as $entity){
            $entities[] = $entity["entity"];
        }
        return $entities;
    }

    /**
     * returns the entity-array as it is saved in the current object
     * @return [type] [description]
     */
    public function getEntitiesArray()
    {
        return $this->entities;
    }

    /**
     * used for setting the form action
     *
     * @param $action
     */
    protected function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * returns the current form action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * used for enabling or disabling uploads in the current form
     *
     * @param $uploads
     */
    protected function setUploads($uploads = true)
    {
        if($uploads === true){
            $this->enctype = "multipart/form-data";
        } else {
            $this->enctype = "";
        }
    }

    /**
     * returns if the current form supports file uploads
     *
     * @return bool
     */
    public function hasUploads()
    {
        return !empty($this->enctype);
    }

    /**
     * sets the http method - the form should be submitted you can only use:
     * POST or GET!
     *
     * @param string $method
     */
    protected function setMethod($method = "POST")
    {
        $method = strtoupper($method);
        if($method == "POST"){
            $this->method = "POST";
        } else {
            $this->method = "GET";
        }
    }

    /**
     * returns the current form enctype
     *
     * @return string
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * returns the http method of the form (GET/POST)
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * returns the name of the current form
     *
     * @return string
     */
    public function getName()
    {
        $class = get_called_class();
        $parts = explode("\\", $class);
        return array_pop($parts);
    }

    /**
     * returns the unique token of the current form
     *
     * @return string
     */
    protected function getToken()
    {
        return $this->token;
    }

    /**
     * generates a random token for identicate the current form
     *
     * @return string
     */
    private function generateToken(){
        $this->token = Hash::random();
        return $this->token;
    }

    /**
     * use this function for adding input-fields to your form object
     *
     * @param Input $input
     */
    public function add(Input $input)
    {
        $name = $input->getName();
        $value = $input->getValue();

        if (empty($value) && !empty($this->entities)) {
            foreach($this->entities as $entity){
                $attributes = $entity["attributes"];
                $entityObj = $entity["entity"];
                $property = array_search($name, $attributes);
                if($property !== false){
                    $method = 'get'.ucfirst($property);
                    $input->setValue($entityObj->$method());
                    break;
                }
            }
        }

        $match = array();
        if(preg_match("/^(.*)\[\]$/", $name, $match)){
            $name = $match[1];
            if($this->has($name)){
                if(is_array($this->inputs[$name])){
                    $this->inputs[$name][] = $input;
                } else {
                    $this->inputs[$name] = array($this->inputs[$name], $input);
                }
            } else {
                $this->inputs[$name] = array($input);
            }
        } else {
            $this->inputs[$name] = $input;
        }
    }

    /**
     * returns if input-field named $name exists in current form
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->inputs[$name]);
    }

    /**
     * returns the input-field named $name. if the input-field does not exists,
     * it will return null. if input-field is saved as an array you need to
     * specify an index.
     *
     * @param $name
     * @param $index
     *
     * @return mixed
     */
    public function get($name, $index = 0)
    {
        if($this->has($name)){
            if(is_array($this->inputs[$name])){
                if(isset($this->inputs[$name][$index])){
                    return $this->inputs[$name][$index];
                }
                return;
            }
            return $this->inputs[$name];
        }
        return;
    }

    /**
     * returns the current value of the input-field named $name.
     * returns null, if the input-field does not exist.
     *
     * @param $name
     *
     * @return mixed
     */
    public function getValue($name)
    {
        if($this->has($name)){
            if(isset(self::$data[$name])){
                return self::$data[$name];
            }
            return $this->get($name)->getValue();
        }
        return;
    }

    /**
     * used for removing input-fields
     *
     * @param $name
     */
    public function remove($name)
    {
        if($this->has($name))
        {
            unset($this->inputs[$name]);
        }
    }

    /**
     * used for resetting (clear) the form data
     */
    public function reset()
    {
        self::$data = array();
    }

    /**
     * use this function for opening a new form
     *
     * @return string
     */
    public function open()
    {
        $token = $this->generateToken();
        $name = $this->getName();
        $enctype = "";
        if($this->hasUploads()){
            $enctype = " enctype='".$this->enctype."'";
        }

        return "<form name='$name' action='".$this->getAction()."' method='".$this->getMethod()."'$enctype><input type='hidden' name='dp-form-name' value='$name'/><input type='hidden' name='dp-form-token' value='$token'/>";
    }

    /**
     * use this function for closing a form
     *
     * @return string
     */
    public function close()
    {
        $this->reset();
        $_SESSION["DP_FORMS"][$this->getName()] = serialize($this);

        return "</form>";
    }

    /**
     * converts input elements to an array (to receive namespace).
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->inputs as $input) {
            if(is_array($input)){
                foreach($input as $item){
                    $array[$item->getName()][] = $item->__toString();
                }
            } else {
                $array[$input->getName()] = $input->__toString();
            }
        }

        return $array;
    }


}
