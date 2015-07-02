<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 05.03.15 - 16:12.
 */
namespace DripsPHP\Log;

/**
 * Class Log.
 *
 * used for logging
 */
class Log
{
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const INFO = 'INFO';

    protected $name;
    protected $path;

    /**
     * create a new logger $name in $path.
     *
     * @param $name
     * @param $path
     */
    public function __construct($name, $path = 'core/log')
    {
        $this->name = $name;
        $this->path = "$path/$name.log";
    }

    /**
     * returns if log does already exists.
     *
     * @return bool
     */
    public function exists()
    {
        return is_file($this->path);
    }

    /**
     * write $msg into the log.
     *
     * @param $msg
     * @param $type = Log::ERROR
     *
     * @return bool
     */
    public function write($msg, $type = self::ERROR)
    {
        $msg = '['.date('Y-m-d H:i:s')."][$type] $msg \n";

        return error_log($msg, 3, $this->path);
    }

    /**
     * clears the log.
     *
     * @return bool
     */
    public function clear()
    {
        if ($this->exists()) {
            return file_put_contents($this->path, '') !== false;
        }

        return false;
    }
}
