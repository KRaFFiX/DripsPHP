<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 30.03.15 - 15:52.
 */
namespace DripsPHP\Database\ORM;

use DripsPHP\MVC\Model;
use Exception;

/**
 * Class Entity.
 *
 * used for access the database via object (ORM)
 */
abstract class Entity extends Model
{
    protected static $TABLE;
    protected static $primary = array();
    protected $attributes = array();
    protected $modified = false;
    protected $empty = true;

    /**
     * creates a new entity instance and set default values.
     */
    public function __construct()
    {
        $this->setDefaultValues();
    }

    /**
     * returns if entity has attribute $name.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * returns if entity is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->empty;
    }

    /**
     * returns db-table of the entity.
     *
     * @return string
     */
    public static function getDBTable()
    {
        $class = get_called_class();

        return $class::$TABLE;
    }

    /**
     * returns if entity has been modified.
     *
     * @return bool
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     * resets modified state from entity.
     */
    public function resetModified()
    {
        $this->modified = false;
    }

    /**
     * returns attributes of the entity.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * returns attribute value of attribute $name.
     *
     * @param $name
     *
     * @return mixed
     *
     * @throws EntityAttributeDoesNotExistException
     */
    public function getAttribute($name)
    {
        if (!$this->hasAttribute($name)) {
            throw new EntityAttributeDoesNotExistException(get_called_class().': '.$name);
        }

        return $this->attributes[$name];
    }

    /**
     * converts $val into a default value for entity attribute. this function is
     * used for replacing placeholders.
     *
     * @param $val
     *
     * @return string
     */
    protected function getDefaultValue($val)
    {
        switch ($val) {
            case '{date}':
                $val = date('Y-m-d');
                break;
            case '{time}':
                $val = date('H:i:s');
                break;
            case '{datetime}':
                $val = date('Y-m-d H:i:s');
                break;
            default:
                break;
        }

        return $val;
    }

    /**
     * sets default values for default fields.
     */
    protected function setDefaultValues()
    {
        foreach ($this->attributes as $attr => $val) {
            $this->attributes[$attr] = $this->getDefaultValue($val);
        }
    }

    /**
     * sets entity attribute $name to $value.
     *
     * @param $name
     * @param $value
     *
     * @return bool
     *
     * @throws EntityAttributeDoesNotExistException
     */
    public function setAttribute($name, $value)
    {
        if (!$this->hasAttribute($name)) {
            throw new EntityAttributeDoesNotExistException(get_called_class().': '.$name);
        }
        $this->modified = true;
        $this->empty = false;
        $this->attributes[$name] = $value;

        return true;
    }

    /**
     * returns primary of the entity (only attribute names).
     *
     * @return mixed
     */
    public static function getPrimary()
    {
        $class = get_called_class();

        return $class::$primary;
    }

    /**
     * returns primary key of the entity (attribute names and values).
     *
     * @return array
     */
    public function getPrimaryKey()
    {
        $pk = self::getPrimary();
        $primary = array();
        for ($i = 0; $i < count($pk); $i++) {
            $primary[$pk[$i]] = $this->attributes[$pk[$i]];
        }

        return $primary;
    }

    /**
     * returns attributes as json.
     *
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->attributes);
    }

    /**
     * returns attributes as json string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJSON();
    }
}

class EntityAttributeDoesNotExistException extends Exception
{
}
