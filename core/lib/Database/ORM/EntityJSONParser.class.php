<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 16.05.15 - 13:08.
 */
namespace DripsPHP\Database\ORM;

use Exception;

/**
 * Class EntityJSONParser.
 *
 * This class allows access to the entity JSON file and convert the file to a
 * EntityData object.
 */
class EntityJSONParser
{
    protected $savePath;
    protected $jsonFile;
    protected $data = array();
    protected static $validate_attributes = array('name', 'table', 'attributes', 'container');

    /**
     * Creates a new instance of the parser for a particular JSON file and
     * checks whether this is valid.
     *
     * @param $json_file
     * @param $entity_dir
     *
     * @throws EntityJSONFileDoesNotExistException
     * @throws EntityJSONDirectoryDoesNotExistException
     */
    public function __construct($json_file, $entity_dir = null)
    {
        if ($entity_dir === null) {
            $entity_dir = dirname($json_file);
        }
        if (!file_exists($json_file)) {
            throw new EntityJSONFileDoesNotExistException($json_file);
        }
        if (!is_dir($entity_dir)) {
            throw new EntityJSONDirectoryDoesNotExistException($entity_dir);
        }
        $this->jsonFile = $json_file;
        $this->savePath = $entity_dir;
        $this->parse();
        $this->validate();
    }

    /**
     * Returns the path where the generated entity is stored.
     *
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * Converts the JSON file into an array.
     */
    protected function parse()
    {
        $this->data = json_decode(file_get_contents($this->jsonFile), true);
    }

    /**
     * Checks whether the entity has all the necessary attributes.
     *
     * @throws EntityJSONWrongSyntaxException
     */
    protected function validate()
    {
        if (!is_array($this->data)) {
            throw new EntityJSONWrongSyntaxException($this->jsonFile);
        }
        foreach (self::$validate_attributes as $attribute) {
            if (!array_key_exists($attribute, $this->data)) {
                throw new EntityJSONWrongSyntaxException("$attribute was not found in ".$this->jsonFile);
            }
        }
        if (!is_array($this->data['attributes'])) {
            throw new EntityJSONWrongSyntaxException('attributes must be an array in '.$this->jsonFile);
        }
    }

    /**
     * Returns an object containing EntityData the data the json file.
     *
     * @return EntityData
     */
    public function getEntityData()
    {
        return new EntityData($this->data);
    }
}

class EntityJSONFileDoesNotExistException extends Exception
{
}

class EntityJSONDirectoryDoesNotExistException extends Exception
{
}

class EntityJSONWrongSyntaxException extends Exception
{
}
