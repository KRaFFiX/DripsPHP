<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 17.02.15 - 08:38.
 */
namespace DripsPHP\Cache;

use Closure;
use DateInterval;

/**
 * Class ViewCache.
 *
 * This class is intended for caching views.
 */
class ViewCache extends Cache
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
    public function get(Closure $content)
    {
        if (!$this->exists()) {
            $this->put(call_user_func($content));
        }

        return $this->file->read();
    }
}
