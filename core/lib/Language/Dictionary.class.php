<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 03.04.15 - 15:10.
 */
namespace DripsPHP\Language;

use Exception;
use ArrayAccess;

/**
 * Class Dictionary.
 *
 * represents a dictionary for looking up texts
 */
class Dictionary implements ArrayAccess
{
    protected $dictionary = array();
    protected $loaded_langs = array();
    protected $package;

    /**
     * creates a new dictionary object.
     *
     * @param $package
     * @param $default_language
     *
     * @throws DictionaryPackageNotFoundException
     * @throws DictionaryInvalidJsonFormatException
     */
    public function __construct($package, $default_language)
    {
        $this->package = $package;
        $this->load($default_language);
    }

    /**
     * loads a new language package.
     *
     * @param $language
     *
     * @return bool
     *
     * @throws DictionaryPackageNotFoundException
     * @throws DictionaryInvalidJsonFormatException
     */
    public function load($language)
    {
        $language = strtolower($language);
        if (in_array($language, $this->loaded_langs)) {
            return true;
        }
        $this->loaded_langs[] = $language;
        $path = 'src/'.$this->package."/langs/$language.json";
        if (!is_file($path)) {
            $path = 'plugins/'.$this->package."/langs/$language.json";
            if (!is_file($path)) {
                throw new DictionaryPackageNotFoundException($path);
            }
        }
        $json = json_decode(file_get_contents($path), true);
        if ($json === null) {
            throw new DictionaryInvalidJsonFormatException($path.': '.json_last_error_msg());
        }
        $this->dictionary = array_merge($this->dictionary, $json);

        return true;
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->dictionary[] = $value;
        } else {
            $this->dictionary[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->dictionary[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->dictionary[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->dictionary[$offset]) ? $this->dictionary[$offset] : null;
    }
}

class DictionaryPackageNotFoundException extends Exception
{
}

class DictionaryInvalidJsonFormatException extends Exception
{
}
