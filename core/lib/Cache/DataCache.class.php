<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 14.02.15 - 15:06.
 */
namespace DripsPHP\Cache;

use Closure;
use DateInterval;

/**
 * Class DataCache.
 *
 * This cache class is primarily intended caching large PHP objects.
 */
class DataCache extends Cache
{
    /**
     * Creates a new cache instance. The specified ID must be unique. Also a time
     * (DateInterval string) is defined as the cache is updated regularly.
     *
     * @param $id
     * @param $diff
     */
    public function __construct($id, $diff)
    {
        $this->id = $id;
        $this->diff = new DateInterval($diff);
        $this->init();
    }

    /**
     * This function returns the contents of the cache.
     * If the file in the cache does not exist or is it out of date, it will be
     * generated automatically before the content is returned.
     *
     * @param callable $content
     *
     * @return mixed
     */
    public function get(Closure $content, $array = false)
    {
        if (!$this->exists()) {
            $this->put(json_encode(call_user_func($content)));
        }

        return json_decode($this->file->read(), $array);
    }
}
