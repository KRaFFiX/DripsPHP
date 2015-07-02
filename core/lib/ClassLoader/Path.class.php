<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 30.05.15 - 14:00.
 */
namespace DripsPHP\ClassLoader;

use Exception;

/**
 * Class Path.
 *
 * DripsPHP used partly for the transfer of namespaces and classes its own
 * notation, in which the namespaces and classes instead of '\' through '.' are
 * separated. In addition, you can specify an optional method.
 * This class is mainly used for resolution of this notation in a namespace, the
 * class, the namespace or the specified method can be called.
 */
class Path
{
    const METHOD_DELIMITER = ':';
    protected $path;

    /**
     * Creates a new instance. As a parameter, a string is passed, containing the
     * Drips notation.
     *
     * @param $path
     *
     * @throws PathIsInvalidException
     */
    public function __construct($path)
    {
        if (!self::isValid($path)) {
            throw new PathIsInvalidException($path);
        }
        $this->path = $path;
    }

    /**
     * Checks by regular expression if the given notation is valid.
     *
     * @param $path
     *
     * @return bool
     */
    public static function isValid($path)
    {
        // format: any.name.space.class[:method]
        return preg_match("/^(\w+\.)+\w+(".self::METHOD_DELIMITER."\w+){0,1}$/", $path);
    }

    /**
     * As parameter a string containing a namespace is passed. Based on the
     * namespaces a Path object is created and returned.
     *
     * @param $namespace
     *
     * @return Path
     */
    public static function getFromNamespace($namespace)
    {
        return new self(ltrim(str_replace('\\', '.', $namespace), '.'));
    }

    /**
     * Returns the namespace of the path as a string. Optionally $begin to be
     * set to True, then the namespace otherwise does not start with a '\'.
     *
     * @param $begin
     *
     * @return string
     */
    public function getNamespace($begin = false)
    {
        $class = $this->getClass($begin);
        $last = strrpos($class, '\\');

        return substr($class, 0, $last);
    }

    /**
     * Returns the name of the class. What can be read from the path.
     *
     * @return string
     */
    public function getClassName()
    {
        $class = $this->getClass();
        $parts = explode('\\', $class);

        return array_pop($parts);
    }

    /**
     * Specifies the full name of the class back, ie Namespace + class.
     * Optionally $begin to be set to true, then the namespace otherwise does
     * not start with a '\'.
     *
     * @param $begin
     *
     * @return string
     */
    public function getClass($begin = false)
    {
        if ($this->hasMethod()) {
            $parts = explode(self::METHOD_DELIMITER, $this->path);
            $class = $parts[0];
        } else {
            $class = $this->path;
        }
        $class = str_replace('.', '\\', $class);

        if ($begin) {
            $class = "\\$class";
        }

        return $class;
    }

    /**
     * If a method specified in the path, it can be queried herewith. If no
     * method exists, an empty string is returned.
     *
     * @return string
     */
    public function getMethod()
    {
        $parts = explode(self::METHOD_DELIMITER, $this->path);
        if (count($parts) == 2) {
            return array_pop($parts);
        }

        return '';
    }

    /**
     * Checks if a method has been specified in the path.
     *
     * @return bool
     */
    public function hasMethod()
    {
        return strpos($this->path, self::METHOD_DELIMITER) !== false;
    }

    /**
     * Returns the path that was specified during the initialization of the
     * object.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the specified path.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPath();
    }
}

class PathIsInvalidException extends Exception
{
}
