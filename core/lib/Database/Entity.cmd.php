<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 19.03.15 - 14:07.
 */
namespace DripsPHP\Database;

use DripsPHP\CLI\Console;
use DripsPHP\Database\ORM\EntityGenerator;
use DripsPHP\CLI\ICMD;

/**
 * Class Entity.
 *
 * This class allows the generation of entities using a JSON file through the CLI.
 */
abstract class Entity implements ICMD
{
    /**
     *	To pass a file path. The specified file must be in JSON format and is
     *	used to generate a Entities. The entity will be created there where the
     *	JSON file is located.
     *
     * @param $json_file
     */
    public static function create($json_file)
    {
        $generator = new EntityGenerator($json_file);
        if ($generator->generateEntity()) {
            Console::success('Entity has successfully been created.');
        } else {
            Console::error('Entity could not been created.');
        }
        if ($generator->generateEntityContainer()) {
            Console::success('EntityContainer has successfully been created.');
        } else {
            Console::error('Entity could not been created.');
        }
    }

    /**
     * Prints the help for the command.
     */
    public static function help()
    {
        Console::writeln('You can generate entities from a json-file.');
        Console::writeln('If you have a valid json-file for creating entities you can use the following command for generating it.');
        Console::setColor('blue');
        Console::writeln('php drips create:Entity {path/to/entity.json}');
        Console::resetColors();
    }
}
