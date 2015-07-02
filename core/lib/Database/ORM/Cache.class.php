<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 01.04.15 - 13:28.
 */
namespace DripsPHP\Database\ORM;

/**
 * Class Cache.
 *
 * This class is used to cache entities which have been retrieved from the
 * database, so you will not have to be created and queried again.
 */
class Cache
{
    protected $entities = array();

    /**
     * returns if $entity already exists in cache.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public function has(Entity $entity)
    {
        foreach ($this->entities as $cachedEntity) {
            if ($cachedEntity->getPrimaryKey() == $entity->getPrimaryKey()) {
                return true;
            }
        }

        return false;
    }

    /**
     * adds $entity to cache if it does not already exist.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public function add(Entity $entity)
    {
        if (!$this->has($entity)) {
            $this->entities[] = $entity;

            return true;
        }

        return false;
    }

    /**
     * updates entity in cache.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public function update(Entity $entity)
    {
        foreach ($this->entities as $key => $cachedEntity) {
            if ($cachedEntity->getPrimaryKey() == $entity->getPrimaryKey()) {
                $this->entities[$key] = $entity;

                return true;
            }
        }

        return false;
    }

    /**
     * removes $entity from cache if it exists.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public function remove(Entity $entity)
    {
        foreach ($this->entities as $key => $cachedEntity) {
            if ($cachedEntity->getPrimaryKey() == $entity->getPrimaryKey()) {
                unset($this->entities[$key]);

                return true;
            }
        }

        return false;
    }

    /**
     * returns $entity from cache or null if it does not exist.
     *
     * @param Entity $entity
     */
    public function get(Entity $entity)
    {
        foreach ($this->entities as $key => $cachedEntity) {
            if ($cachedEntity->getPrimaryKey() == $entity->getPrimaryKey()) {
                return $cachedEntity;
            }
        }

        return;
    }

    /**
     * returns all entities from cache.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->entities;
    }
}
