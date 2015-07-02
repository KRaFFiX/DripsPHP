<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.02.15 - 16:50.
 */
namespace DripsPHP\Validator;

use Exception;
use ReflectionClass;

/**
 * Class Validator.
 *
 * Using these class entries can be verified or validated.
 */
class Validator
{
    protected $string = '';
    protected $checks = array();
    protected $results = array();
    protected $validated = false;
    protected $validateResult = false;

    /**
     * Creates a new validator instance.
     * There can be any number of (or no) arguments.
     * Any passed parameters must be either a string containing the name of the
     * validator, or an array with the name of the validator and the parameters
     * which are to be passed to the validator.
     *
     * @throws NotAValidValidatorException
     * @throws ValidatorDoesNotExistException
     */
    public function __construct()
    {
        // get array of args
        $validators = func_get_args();
        // one argument means one validator
        foreach ($validators as $validator) {
            // if is array - validator has parameters
            if (is_array($validator)) {
                // key of array is the name of the validator
                $key = array_keys($validator)[0];
                // values of the array are parameters for the validator
                $args = $validator[$key];
                $this->add($key, $args);
            } else {
                $this->add($validator);
            }
        }
    }

    /**
     * Sets the input string, which should be validated.
     *
     * @param $str
     */
    public function set($str)
    {
        $this->string = $str;
        // reset validation results
        $this->validated = false;
        $this->validateResult = false;
    }

    /**
     * Adds another validator added so that multiple validators can be combined
     * and then a new validator can be created.
     *
     * @param $validator
     * @param array $args
     *
     * @return $this
     *
     * @throws NotAValidValidatorException
     * @throws ValidatorDoesNotExistException
     */
    public function add($validator, $args = array())
    {
        // every validator needs to start with an uppercase letter, because the
        // classname is also namen with first letter upper. You can also pass
        // only lowercase validator-names because the are automatically getting
        // first letter uppercase.
        $validator = ucfirst($validator);
        // create full namespace of the validator
        $class = __NAMESPACE__."\\Validators\\$validator";
        // does the validator class exists at all
        if (class_exists($class)) {
            // if class is a valid IValidator
            if (new $class instanceof IValidator) {
                // save validator for validation
                $this->checks[$validator]['class'] = $class;
                $this->checks[$validator]['args'] = $args;

                return $this;
            } else {
                throw new NotAValidValidatorException($class);
            }
        } else {
            throw new ValidatorDoesNotExistException($class);
        }
    }

    /**
     * Checks if the given string is correct or valid.
     * If the string is already checked, so no re-examination is carried out but
     * returned the result of the previous test.
     *
     * @return bool
     */
    public function validate()
    {
        // has the specified string already been validated
        if ($this->validated) {
            return $this->validateResult;
        }
        $errors = false;
        foreach ($this->checks as $options) {
            //$classparts = explode('\\', $options['class']);
            //$classname = strtolower(array_pop($classparts));
            $classname = (new ReflectionClass($options['class']))->getShortName();
            $this->results[strtolower($classname)] = true;
            // if args is an array
            if (is_array($options['args'])) {
                // call validate method of validator class with params and str
                if (!call_user_func_array(array($options['class'], 'validate'), array_merge(array($this->string), $options['args']))) {
                    $this->results[strtolower($classname)] = false;
                    $errors = true;
                }
            } else {
                // call validate method of validator class with string and param
                if (!call_user_func(array($options['class'], 'validate'), $this->string, $options['args'])) {
                    $this->results[strtolower($classname)] = false;
                    $errors = true;
                }
            }
        }
        $this->validated = true;
        $this->validateResult = !$errors;

        return $this->validateResult;
    }

    /**
     * Returns an array of boolean values. The array shows the validators were
     * successful and which have failed.
     * If no validation has been carried out with the validate method, this is
     * done automatically here.
     *
     * @return array
     */
    public function getResults()
    {
        // has not the specified string already been validated
        if (!$this->validated) {
            // then validate
            $this->validate();
        }

        return $this->results;
    }
}

class NotAValidValidatorException extends Exception
{
}

class ValidatorDoesNotExistException extends Exception
{
}
