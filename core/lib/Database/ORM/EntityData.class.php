<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 16.05.15 - 13:38.
 */
namespace DripsPHP\Database\ORM;

use DripsPHP\ClassLoader\Path;

/**
 * Class EntityData.
 *
 * This class is created by EntityJSONParser and mainly includes the data of the
 * JSON file. The values can be easily queried with the help of the class.
 */
class EntityData
{
    protected $data;

    /**
     * creates a new object of EntityData
     * Will pass the array, which was generated from the JSON file.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * returns the name of the entity
     * because it will become a class name first letter is uppercase.
     *
     * @return string
     */
    public function getName()
    {
        return ucfirst($this->data['name']);
    }

    /**
     * returns the table name of the entity from database.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->data['table'];
    }

    /**
     * returns the name of the entity container
     * because it will become a class name first letter is uppercase.
     *
     * @return string
     */
    public function getContainer()
    {
        return ucfirst($this->data['container']);
    }

    /**
     * returns the properties of the entity
     * if $withProperties is true you will receive properties and their values
     * otherwise you will receive only the property names.
     *
     * @param $withProperties
     *
     * @return array
     */
    public function getAttributes($withProperties = false)
    {
        if ($withProperties) {
            return $this->data['attributes'];
        }

        return array_keys($this->data['attributes']);
    }

    /**
     * returns the primary key as an array (not the value).
     *
     * @return array
     */
    public function getPrimaryKey()
    {
        $primaries = array();
        foreach ($this->getAttributes() as $attribute) {
            if ($this->hasOptions($attribute)) {
                if ($this->isPrimary($attribute)) {
                    $primaries[] = $attribute;
                }
            }
        }

        return $primaries;
    }

    /**
     * returns if attribute with $name exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->getAttributes());
    }

    /**
     * returns if option with $name exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasOptions($name)
    {
        return array_key_exists('options', $this->data['attributes'][$name]);
    }

    /**
     *	returns if attribute ($name) is a part of primary key.
     *
     * @param $name
     *
     * @return bool
     */
    public function isPrimary($name)
    {
        return in_array('primary', $this->data['attributes'][$name]['options']);
    }

    /**
     * returns if attribute ($name) is allowed to become null.
     *
     * @param $name
     *
     * @return bool
     */
    public function isNullable($name)
    {
        if (!$this->hasOptions($name)) {
            return false;
        }

        return in_array('nullable', $this->data['attributes'][$name]['options']) || $this->isPrimary($name);
    }

    /**
     * returns if attribute ($name) references an another entity.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasReference($name)
    {
        return array_key_exists('references', $this->data['attributes'][$name]);
    }

    /**
     * returns the reference of the attribute
     * if attribute does not have a reference it will return null.
     *
     * @param $name
     *
     * @return string|null
     */
    public function getReference($name)
    {
        if ($this->hasReference($name)) {
            return $this->data['attributes'][$name]['references'];
        }

        return;
    }

    /**
     * returns the table name of the reference object
     * if it could not been found it will return null.
     *
     * @param $name
     *
     * @return string|null
     */
    public function getReferencesTable($name)
    {
        if (!$this->hasReference($name)) {
            return;
        }
        $path = Path::getFromNamespace($this->getReference($name));
        $reference = $path->getPath();

        return $reference::getTable();
    }

    /**
     * returns the datatype of the attribute ($name)
     * if no one was given it will return default type which is text.
     *
     * @param $name
     *
     * @return string
     */
    public function getType($name)
    {
        if (!array_key_exists('type', $this->data['attributes'][$name])) {
            return Datatype::Text;
        }

        return $this->data['attributes'][$name]['type'];
    }

    /**
     * returns if the attribute ($name) has validators.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasValidators($name)
    {
        return array_key_exists('validators', $this->data['attributes'][$name]);
    }

    /**
     * returns the validators of the attribute ($name).
     *
     * @param $name
     *
     * @return array
     */
    public function getValidators($name)
    {
        return $this->data['attributes'][$name]['validators'];
    }

    /**
     * returns if the attribute ($name) has a default value.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasDefault($name)
    {
        return array_key_exists('default', $this->data['attributes'][$name]);
    }

    /**
     * returns if the attribute ($name) has a static value
     * a static value means that it is not a placeholder
     * placeholder has the following format: {placeholder}.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasStaticDefault($name)
    {
        if ($this->hasDefault($name)) {
            $default = $this->getDefault($name);

            return !preg_match("/\{\w{1,}\}/", $default);
        }

        return false;
    }

    /**
     * returns the default value of the attribute ($name)
     * if it does not have a default value it will return null.
     *
     * @param $name
     *
     * @return string|null
     */
    public function getDefault($name)
    {
        if ($this->hasDefault($name)) {
            return $this->data['attributes'][$name]['default'];
        }

        return;
    }

    /**
     * returns if the attribute ($name) should be auto increment.
     *
     * @param $name
     *
     * @return bool
     */
    public function isAutoIncrement($name)
    {
        if (!$this->hasOptions($name)) {
            return false;
        }

        return in_array('auto', $this->data['attributes'][$name]['options']);
    }
}
