<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 15:59.
 */
namespace DripsPHP\Database;

use Exception;

/**
 * Class DB.
 *
 * used for direct access
 * to database without using an object
 */
abstract class DB
{
    private static $connection;

    /**
     * connects to a database, using a Database object.
     *
     * @param Database $db
     */
    public static function connect(Database $db)
    {
        self::$connection = $db;
    }

    /**
     * returns current database-connection (object).
     *
     * @return Database
     */
    public static function getConnection()
    {
        return self::$connection;
    }

    /**
     * gives called functions to the
     * connection and returns the result.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws DBNoDatabaseFoundException
     */
    public static function __callStatic($name, $arguments)
    {
        if (!isset(self::$connection)) {
            throw new DBNoDatabaseFoundException();
        }

        return call_user_func_array(array(self::$connection, $name), $arguments);
    }
}

class DBNoDatabaseFoundException extends Exception
{
}
