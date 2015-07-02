<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 14.02.15 - 12:56.
 */
namespace DripsPHP\Cache;

use Closure;
use Exception;
use DripsPHP\FileSystem\File;

/**
 * Class CompileCache.
 *
 * This class is responsible for caching compiled files.
 * This means that the file in the cache is valid until the content changes.
 */
class CompileCache extends Cache
{
    protected $lastChanges;
    protected $cachedFile;
    protected $path;

    /**
     * Creates a new cache element. It is important to pass a unique ID through
     * which the file in the cache can be identified.
     * In addition, a path to the file must be specified, which should be
     * monitored for changes, so that the cache can compile the file again.
     *
     * @param $id
     * @param $path
     *
     * @throws Exception
     */
    public function __construct($id, $path)
    {
        $this->id = $id;
        $this->path = new File($path);
        if (!$this->path->isReadable()) {
            throw new Exception('CompilerCache '.$path.' is not readable');
        }
        $this->lastChanges = $this->path->getLastModification();
        $this->init();
    }

    /**
     * This function returns the contents of the cache.
     * If the file in the cache does not exist or is it out of date, it will be
     * generated automatically before the content is returned.
     *
     * @param callable $content
     *
     * @throws Exception
     *
     * @return string
     */
    public function get(Closure $content = null)
    {
        if (!$this->exists()) {
            if ($content === null) {
                $content = $this->path->read();
            } else {
                $content = call_user_func($content);
            }
            $this->put($content);
        }

        if (!$this->file->isWritable()) {
            throw new Exception("CompilerCache can't write in cache - no permissions");
        }

        return $this->file->read();
    }

    /**
     * Returns whether the content exists in the cache, and whether it is still
     * up to date.
     *
     * @return bool
     */
    public function exists()
    {
        if ($this->file->exists()) {
            $creation = (int) date('YmdHis', $this->file->getLastModification());
            $lastChanges = (int) date('YmdHis', $this->lastChanges);

            return $creation > $lastChanges;
        }

        return false;
    }
}
