<?php

/**
 * Created by Prowect.
 * User: Raffael Kessler
 * Date: 04.10.14
 * Time: 17:14.
 */
namespace DripsPHP\Generators;

/**
 * Class ClassGenerator.
 *
 * used for generating new php-classes
 */
class ClassGenerator
{
    protected $classname;
    protected $extends = '';
    protected $namespace;
    protected $attributes = array();
    protected $methods = array();

    /**
     * creates a new class generator instance which means a new class.
     * You need to determine a classname and if you want you can inherit an another
     * class.
     *
     * @param $classname
     * @param string $extends
     */
    public function __construct($classname, $extends = '')
    {
        $this->classname = $classname;
        $this->extends = $extends;
    }

    /**
     * sets the namespace for the class.
     *
     * @param $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * adds an attribute to the class.
     *
     * @param $name
     * @param null   $value
     * @param string $visibility
     * @param bool   $static
     */
    public function addAttribute($name, $value = null, $visibility = 'protected', $static = false)
    {
        $this->attributes[$name] = array('value' => $value, 'visibility' => $visibility, 'static' => $static);
    }

    /**
     * adds a new method to the class.
     *
     * @param $name
     * @param array  $params
     * @param string $visibility
     * @param bool   $static
     * @param string $method
     */
    public function addMethod($name, $params = array(), $visibility = 'public', $static = false, $method = '')
    {
        $this->methods[$name] = array('params' => $params, 'visibility' => $visibility, 'static' => $static, 'method' => $method);
    }

    /**
     * generates the php-code for an class
     * and returns it as string.
     *
     * @param bool $withPHP
     *
     * @return string
     */
    public function generate($withPHP = false)
    {
        $class = '';
        // <?php
        if ($withPHP) {
            $class .= "<?php\n";
        }
        // Comment
        $class .= "/** Generated with DripsPHP ClassGenerator */\n\r";
        // Namespace
        if (isset($this->namespace)) {
            $class .= 'namespace '.$this->namespace.";\n\r";
        }
        // Class
        $class .= 'class '.ucfirst($this->classname).' '.$this->extends." \n{\n";
        // Attributes
        foreach ($this->attributes as $attribute => $properties) {
            $class .= $properties['visibility'].($properties['static'] ? ' static ' : ' ').'$'.$attribute.($properties['value'] !== null ? ' = '.$properties['value'] : '').";\n";
        }
        $class .= "\r\n";
        // Methods
        foreach ($this->methods as $method => $properties) {
            $class .= $properties['visibility'].($properties['static'] ? ' static ' : ' ').'function '.$method.'(';
            $first = true;
            foreach ($properties['params'] as $param) {
                if (!$first) {
                    $class .= ', ';
                }
                $class .= '$'.$param;
                $first = false;
            }
            $class .= ")\n{\n".$properties['method']."\n}\n\r\n";
        }
        $class .= "\r}";

        return $class;
    }
}
