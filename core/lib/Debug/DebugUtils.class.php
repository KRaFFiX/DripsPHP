<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 03.05.15 - 13:13.
 */
namespace DripsPHP\Debug;

use ReflectionClass;

/**
 * Class DebugUtils.
 *
 * This class provides auxiliary functions for debugging.
 */
abstract class DebugUtils
{
    private static $stringStyle = 'color:#b5bd68;';
    private static $propertyStyle = 'color:#b294bb;';
    private static $classStyle = 'color:#de935f;';
    private static $nullStyle = 'color:#81a2be;';
    private static $integerStyle = 'color:#cc6666;';
    private static $doubleStyle = 'color:#cc6666;';
    private static $boolStyle = 'color:#81a2be;';
    private static $arrayStyle = '';
    private static $resourceStyle = '';
    private static $privateStyle = '';
    private static $unknownTypeStyle = '';

    /**
     * alternative function for var_dump or print_r.
     *
     * @param $var
     * @param int $indentBy
     * @param int $indentationLevel
     */
    public static function dump($var, $indentBy = 2, $indentationLevel = 0)
    {
        if (CLI) {
            var_dump($var);
        } else {
            if ($indentationLevel == 0) {
                echo '<pre style="background-color:#1d1f21;color:#c5c8c6;padding:1em;">'.PHP_EOL;
            }

            $parentIndentation = '';
            $indentation = '';
            for ($i = 0; $i < $indentationLevel; $i++) {
                $parentIndentation .= ' ';
            }
            for ($i = 0; $i < $indentBy; $i++) {
                $indentation .= ' ';
            }
            if ($var === null) {
                echo '<span style="'.self::$nullStyle.'">NULL</span>'.PHP_EOL;
            } elseif (is_array($var)) {
                $array = (array) $var;
                $type = 'array';
                $len = sizeof($array);
                echo $type.' ('.$len.') '.'{';
                if ($len > 0) {
                    echo PHP_EOL;
                    foreach ($array as $key => $value) {
                        echo $parentIndentation.$indentation.'['.$key.'] =&gt; ';
                        self::dump($value, $indentBy, $indentationLevel + $indentBy);
                    }
                    echo $parentIndentation;
                }
                echo '}'.PHP_EOL;
            } elseif (is_object($var)) {
                $type = 'object';
                $reflect = new ReflectionClass($var);
                $properties = $reflect->getProperties();
                $len = sizeof($properties);
                echo $type.'<span style="'.self::$classStyle.'">('.$reflect->getName().')</span>'.' ('.$len.') '.'{'.PHP_EOL;
                foreach ($properties as $property) {
                    echo $parentIndentation.$indentation.'<span style="'.self::$propertyStyle.'">['.($property->isStatic() ? 'static ' : '').($property->isPublic() ? 'public ' : ($property->isProtected() ? 'protected ' : ($property->isPrivate() ? 'private ' : ''))).'$'.$property->getName().']</span> =&gt; ';
                    $property->setAccessible(true);
                    self::dump($property->getValue($var), $indentBy, $indentationLevel + $indentBy);
                }
                echo $parentIndentation.'}'.PHP_EOL;
            } elseif (is_string($var)) {
                echo gettype($var).' ('.strlen($var).') <span style="'.self::$stringStyle.'">"'.$var.'"</span>'.PHP_EOL;
            } elseif (is_bool($var)) {
                echo gettype($var).' <span style="'.self::$boolStyle.'">'.(($var == 1) ? 'true' : 'false').'</span>'.PHP_EOL;
            } else {
                $type = gettype($var);
                echo $type.' ('.sizeof($var).') <span style="'.self::${str_replace(' t', 'T', gettype($var)).'Style'}.'">'.$var.'</span>'.PHP_EOL;
            }

            if ($indentationLevel == 0) {
                echo '</pre>';
            }
        }
    }
}
