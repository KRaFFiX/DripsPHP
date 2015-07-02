<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 22.03.15 - 14:51.
 */
namespace DripsPHP\FileSystem;

/**
 * Class Directory.
 *
 * OOP access for directory
 */
class Directory
{
    protected $path;

    /**
     * create a new directory object with $path.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * returns the path (setted in constructor).
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * returns if path exists and is a directory.
     *
     * @return bool
     */
    public function exists()
    {
        return is_dir($this->path);
    }

    /**
     * creates a new directory if it does not
     * exist with mode $mode.
     *
     * @param int $mode
     *
     * @return bool
     */
    public function create($mode = 0777)
    {
        if (!$this->exists()) {
            return mkdir($this->path, $mode);
        }

        return false;
    }

    /**
     * removes the directory if it does
     * exist.
     *
     * @return bool
     */
    public function remove()
    {
        if ($this->exists()) {
            rmdir($this->path);
        }

        return false;
    }

    /**
     * returns the path of the directory.
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * returns if the file is writeable.
     *
     * @return bool
     */
    public function isWritable()
    {
        return is_writable($this->path);
    }

    /**
     * returns files and directories in the directory
     * returns empty array if it does not exist.
     *
     * @param null $pattern
     *
     * @return array
     */
    public function getFiles($pattern = null)
    {
        if ($this->exists()) {
            $files = array();
            $fileObjs = array();
            if ($pattern !== null) {
                $files = glob($this->path."/$pattern");
            } else {
                $files = scandir($this->path);
            }
            foreach ($files as $file) {
                if ($file != '..' && $file != '.') {
                    if (is_dir($file)) {
                        $fileObjs[] = new self($file);
                    } else {
                        $fileObjs[] = new File($file);
                    }
                }
            }

            return $fileObjs;
        }

        return array();
    }
}
