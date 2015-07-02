<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 13.03.15 - 17:24.
 */
namespace DripsPHP\Generators;

/**
 * Class PackageGenerator.
 *
 * used for creating packages in src folder
 */
class PackageGenerator
{
    protected static $directories = array('assets', 'controllers', 'entities', 'langs', 'models', 'views');
    protected static $files = array('routes.json');

    /**
     * generates a new package with $name in src directory.
     *
     * @param $name
     *
     * @return bool
     */
    public static function generate($name)
    {
        $path = "src/$name";
        if (mkdir($path)) {
            // create directories
            foreach (self::$directories as $directory) {
                $newPath = "$path/$directory";
                if (!mkdir($newPath)) {
                    return false;
                }
            }
            // create files
            foreach (self::$files as $file) {
                $newPath = "$path/$file";
                if (file_put_contents($newPath, '') === false) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
