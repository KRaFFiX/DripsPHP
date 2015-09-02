<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 15:51.
 */
namespace DripsPHP\Database;

use Exception;

/**
 * Class Database.
 *
 * used for connecting to a database using Medoo framework
 * The special thing about the class is that it only establishes a connection as
 * soon as an query is executed.
 */
class Database extends Medoo
{
    protected $connected = false;

    /**
     * fix for: pdo not serializable.
     *
     * @return array
     */
    public function __sleep()
    {
        // pdo not serializable fix
        return array();
    }

    /**
     * create a new database connection object
     * with $type of (mysql, sqlite, mssql, ...)
     * from PDO.
     * possible types you can find in DBType class.
     *
     * @param $type
     */
    public function __construct($type)
    {
        if (DBType::exists($type)) {
            $this->database_type = $type;
        } else {
            throw new DatabaseTypeIsNotSupportedException($type);
        }
    }

    /**
     * set options for the connection.
     *
     * @param $options
     */
    public function setOptions($options)
    {
        $this->option = $options;
    }

    /**
     * set database-host.
     *
     * @param $host
     */
    public function setHost($host)
    {
        $this->server = $host;
        $this->database_file = $host;
    }

    /**
     * set database-user.
     *
     * @param $user
     */
    public function setUser($user)
    {
        $this->username = $user;
    }

    /**
     * set database-password.
     *
     * @param $pwd
     */
    public function setPassword($pwd)
    {
        $this->password = $pwd;
    }

    /**
     * set database-port.
     *
     * @param $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * set the database which should be used.
     *
     * @param $db
     */
    public function setDatabase($db)
    {
        $this->database_name = $db;
    }

    /**
     * returns if already connected to
     * the database.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * connects to the database.
     *
     * @throws Exception
     */
    public function connect()
    {
        parent::__construct();
        $this->connected = true;
    }

    /**
     * automatically connects to the database if needed.
     */
    public function __get($name)
    {
        if ($name == 'pdo' && !isset($this->$name)) {
            $this->connect();

            return $this->$name;
        }
    }
}

class DatabaseTypeIsNotSupportedException extends Exception
{
}
