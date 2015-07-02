<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 14.02.15 - 13:00.
 */
namespace DripsPHP\Cache;

use DateTime;
use Closure;
use DripsPHP\FileSystem\File;

/**
 * Class Cache.
 *
 * This class serves as a template for the different caches.
 * Basically, you can check if a file with a given ID already exists in the cache.
 * In addition, the cache file can be created or written and updated.
 * The file in the cache can also be removed.
 */
abstract class Cache implements ICache
{
    const CACHEDIR = 'core/tmp/.cache';

    protected $id;
    protected $diff;
    protected $file;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->file = new File(self::CACHEDIR.'/'.$this->id);
    }

    /**
     * overwrites the file in the cache with $content.
     *
     * @param $content
     *
     * @return int
     */
    public function put($content)
    {
        if ($this->file->isWritable()) {
            return $this->file->write($content);
        }

        return false;
    }

    /**
     * deletes the file from the cache.
     *
     * @return bool
     */
    public function clear()
    {
        return $this->file->delete();
    }

    /**
     * returns if the file exists in the cache, and whether it is still up to date.
     *
     * @return bool
     */
    public function exists()
    {
        // if is already in cache
        if ($this->file->exists()) {
            // last time when cache file has been generated
            $filetime = new DateTime(date('d.m.Y H:i:s', $this->file->getLastModification()));
            // current time
            $now = new DateTime(date('d.m.Y H:i:s'));
            // difference between now and filetime
            $currentDiff = $now->diff($filetime);
            // return if difference has been exceeded
            return (int) $this->diff->format('%Y%M%D%H%I%S') > (int) $currentDiff->format('%Y%M%D%H%I%S');
        }

        return false;
    }

    abstract public function get(Closure $content);
}
