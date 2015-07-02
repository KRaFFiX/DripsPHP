<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 28.05.15 - 13:40.
 */
namespace DripsPHP\Database;

/**
 * Class DBType.
 *
 * This class includes all sorts of database systems, which can be used.
 */
class DBType
{
    const mariadb = 'mariadb';
    const mysql = 'mysql';
    const pgsql = 'pgsql';
    const sybase = 'sybase';
    const oracle = 'oracle';
    const mssql = 'mssql';
    const sqlite = 'sqlite';

    /**
     * Returns whether the specified database type is supported.
     *
     * @param $dbtype
     *
     * @return bool
     */
    public static function exists($dbtype)
    {
        return defined("static::$dbtype");
    }
}
