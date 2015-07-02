<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 22.03.15 - 13:43.
 */
namespace DripsPHP\FileSystem;

/**
 * Class File.
 *
 * OOP access for file
 */
class File
{
    protected $path;

    /**
     * create a new file object with $path.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * destructor clears statcache.
     */
    public function __destruct()
    {
        $this->clearstatcache();
    }

    /**
     * returns if path exists and is a file.
     *
     * @return bool
     */
    public function exists()
    {
        return is_file($this->path);
    }

    /**
     * returns the path setted in constructor.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * changes group of the file if it exists.
     *
     * @param $group
     *
     * @return bool
     */
    public function chgrp($group)
    {
        if ($this->exists()) {
            return chgrp($this->path, $group);
        }

        return false;
    }

    /**
     * changes permissions of the file if it exists.
     *
     * @param $mode
     *
     * @return bool
     */
    public function chmod($mode)
    {
        if ($this->exists()) {
            return chmod($this->path, $mode);
        }

        return false;
    }

    /**
     * changes owner of the file if it exists.
     *
     * @param $user
     *
     * @return bool
     */
    public function chown($user)
    {
        if ($this->exists()) {
            return chown($this->path, $user);
        }

        return false;
    }

    /**
     * clears the statcache.
     */
    public function clearstatcache()
    {
        clearstatcache();
    }

    /**
     * copy file to destination if it exists
     * destination could be String or a file-object.
     *
     * @param $destination
     *
     * @return bool
     */
    public function copy($destination)
    {
        if (is_a($destination, __CLASS__)) {
            $path = $destination->getPath();
        } else {
            $path = $destination;
        }
        if ($this->exists()) {
            return copy($this->path, $path);
        }

        return false;
    }

    /**
     * deletes the file if it exists.
     *
     * @return bool
     */
    public function delete()
    {
        if ($this->exists()) {
            return unlink($this->path);
        }

        return false;
    }

    /**
     * returns the directory (as directory-object) the file are in
     * if file does not exists it will return false.
     *
     * @return bool|Directory
     */
    public function getDirectory()
    {
        return new Directory(dirname($this->path));
    }

    /**
     * returns content of the file or false if it does not exist.
     *
     * @return bool|string
     */
    public function read()
    {
        if ($this->isReadable()) {
            return file_get_contents($this->path);
        }

        return false;
    }

    /**
     * writes $text into the file
     * also used for creating files.
     *
     * @param $text
     * @param bool $append
     *
     * @return bool
     */
    public function write($text = '', $append = false)
    {
        if ($this->isWritable()) {
            if ($append) {
                $result = file_put_contents($this->path, $text, FILE_APPEND);
            } else {
                $result = file_put_contents($this->path, $text);
            }

            return $result !== false;
        }

        return false;
    }

    /**
     * append $text to file.
     *
     * @param $text
     */
    public function append($text)
    {
        $this->write($text, true);
    }

    /**
     * returns last access or atime of the file.
     *
     * @return bool|int
     */
    public function getLastAccess()
    {
        if ($this->exists()) {
            return fileatime($this->path);
        }

        return false;
    }

    /**
     * returns last modification or mtime of the file.
     *
     * @return bool|int
     */
    public function getLastModification()
    {
        if ($this->exists()) {
            return filemtime($this->path);
        }

        return false;
    }

    /**
     * returns the group of the file
     * if $resolve you get the string otherwise you will
     * get an int
     * returns false if the file does not exist.
     *
     * @param bool $resolve
     *
     * @return array|bool|int
     */
    public function getGroup($resolve = true)
    {
        if ($this->exists()) {
            $gid = filegroup($this->path);
            if ($resolve) {
                $gid = posix_getgrgid($gid);
            }

            return $gid;
        }

        return false;
    }

    /**
     * returns the owner of the file
     * if $resolve you get the string otherwise you will
     * get an int
     * returns false if the file does not exist.
     *
     * @param bool $resolve
     *
     * @return array|bool|int
     */
    public function getOwner($resolve = true)
    {
        if ($this->exists()) {
            $uid = fileowner($this->path);
            if ($resolve) {
                $uid = posix_getpwuid($uid);
            }

            return $uid;
        }

        return false;
    }

    /**
     * returns the permissions of the file
     * returns false if the file does not exist.
     *
     * @return bool|int
     */
    public function getPerms()
    {
        if ($this->exists()) {
            return fileperms($this->path);
        }

        return false;
    }

    /**
     * returns the size of the file or false
     * if it does not exist.
     *
     * @return bool|int
     */
    public function getSize()
    {
        if ($this->exists()) {
            return filesize($this->path);
        }

        return false;
    }

    /**
     * returns if the file is executable.
     *
     * @return bool
     */
    public function isExecutable()
    {
        if ($this->exists()) {
            return is_executable($this->path);
        }

        return false;
    }

    /**
     * returns if the file is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        return is_readable($this->path);
    }

    /**
     * returns if the file is writeable.
     *
     * @return bool
     */
    public function isWritable()
    {
        if (is_writable($this->path)) {
            return true;
        }
        $directory = $this->getDirectory();

        return $directory->isWritable() && !$this->exists();
    }

    /**
     * returns some information of the file.
     *
     * @return array|mixed
     */
    public function getInfo()
    {
        if ($this->exists()) {
            return pathinfo($this->path);
        }

        return array();
    }

    /**
     * returns the real path of the file.
     *
     * @return string
     */
    public function getRealPath()
    {
        return realpath($this->path);
    }

    /**
     * rename a file to $newName.
     *
     * @param $newName
     *
     * @return bool
     */
    public function rename($newName)
    {
        if ($this->exists()) {
            $directory = dirname($this->path);

            return rename($this->path, "$directory/$newName");
        }

        return false;
    }

    /**
     * touch file.
     *
     * @return bool
     */
    public function touch()
    {
        return touch($this->path);
    }

    /**
     * return file content.
     *
     * @return bool|string
     */
    public function __toString()
    {
        return $this->read();
    }
}
