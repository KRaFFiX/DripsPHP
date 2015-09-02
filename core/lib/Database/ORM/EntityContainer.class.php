<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 30.03.15 - 19:10.
 */
namespace DripsPHP\Database\ORM;

use DripsPHP\Database\Database;
use DripsPHP\Database\DB;
use Iterator;
use ArrayAccess;
use Exception;
use Closure;

/**
 * Class EntityContainer.
 *
 * This class is used for communication between entities and database. The
 * EntityContainer can contain multiple entities.
 */
class EntityContainer implements Iterator, ArrayAccess
{
    private $position = 0;

    protected static $TABLE;
    protected static $entity;
    protected static $cache;
    protected static $getAll;
    protected static $dbCon;

    protected $entities = array();

    /**
     * returns a new entity container instance.
     *
     * @param array $entities
     */
    public function __construct($entities = array())
    {
        $this->position = 0;
        if (!is_array($entities)) {
            $entities = array($entities);
        }
        foreach ($entities as $entity) {
            if (self::isCorrectType($entity)) {
                $this->entities[] = $entity;
            }
        }
    }

    /**
     * inserts all entities from entity container into db.
     *
     * @return bool
     */
    public function insertAll()
    {
        self::getDBConnection()->beginTransaction();
        foreach ($this as $entity) {
            if (!self::insert($entity)) {
                self::getDBConnection()->rollback();

                return false;
            }
        }

        return self::getDBConnection()->commit();
    }

    /**
     * updates all entities from entity container.
     *
     * @param callable $function
     *
     * @return bool
     */
    public function updateAll(Closure $function)
    {
        self::getDBConnection()->beginTransaction();
        foreach ($this as $entity) {
            $entity = call_user_func($function, $entity);
            if (!self::update($entity)) {
                self::getDBConnection()->rollback();

                return false;
            }
        }

        return self::getDBConnection()->commit();
    }

    /**
     * deletes all entities from entity container in db.
     *
     * @return bool
     */
    public function deleteAll()
    {
        self::getDBConnection()->beginTransaction();
        foreach ($this as $entity) {
            if (!self::delete($entity)) {
                self::getDBConnection()->rollback();

                return false;
            }
        }

        return self::getDBConnection()->commit();
    }

    /**
     * returns the db-table of the entity container.
     *
     * @return string
     */
    public static function getTable()
    {
        return static::$TABLE;
    }

    /**
     * returns the entity of the entity container.
     *
     * @return string
     */
    public static function getEntity()
    {
        return static::$entity;
    }

    /**
     * returns the db connection of the entity container.
     * If no db connection has been specified DB will be used.
     *
     * @return mixed
     */
    protected static function getDBConnection()
    {
        if (!isset(static::$dbCon)) {
            return DB::getConnection();
        }

        return static::$dbCon;
    }

    /**
     * specifies the database for the entity container.
     *
     * @return mixed
     */
    public static function setDBConnection(Database $db)
    {
        static::$dbCon = $db;
    }

    /**
     * returns if already selected all.
     *
     * @return bool
     */
    public static function alreadyGetAll()
    {
        static::$getAll;
    }

    /**
     * returns if $entity is from correct type (uses same db-table).
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public static function isCorrectType(Entity $entity)
    {
        return self::getTable() == $entity->getDBTable();
    }

    /**
     * returns if $entity exists.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public static function has(Entity $entity)
    {
        $table = self::getTable();
        if (self::isCorrectType($entity)) {
            return self::getDBConnection()->has($table, array('AND' => $entity->getPrimaryKey()));
        }

        return false;
    }

    /**
     * returns if $entity has successfully been inserted.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public static function insert(Entity $entity)
    {
        if (self::isCorrectType($entity) && !$entity->isEmpty()) {
            $table = self::getTable();
            $result = self::getDBConnection()->insert($table, $entity->getAttributes());
            if (!$result) {
                return false;
            }
            $cache = self::getCache();
            $cache->add($entity);

            return $result;
        }

        return false;
    }

    /**
     * returns if $entity has successfully been deleted.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public static function delete(Entity $entity)
    {
        if (self::isCorrectType($entity)) {
            $table = self::getTable();
            $result = self::getDBConnection()->delete($table, array('AND' => $entity->getPrimaryKey()));
            if (!$result) {
                return false;
            }
            $cache = self::getCache();
            $cache->remove($entity);

            return $result;
        }

        return false;
    }

    /**
     * returns if $entity has successfully been updated.
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public static function update(Entity $entity)
    {
        if (self::isCorrectType($entity)) {
            $table = self::getTable();
            if (!$entity->isModified()) {
                return true;
            }
            $result = self::getDBConnection()->update($table, $entity->getAttributes(), array('AND' => $entity->getPrimaryKey()));
            if (!$result) {
                return false;
            }
            $cache = self::getCache();
            $entity->resetModified();
            $cache->update($entity);

            return $result;
        }

        return false;
    }

    /**
     * returns entity with primary key data.
     *
     * @param $primary
     *
     * @return mixed
     */
    public static function primaryToEntity($primary)
    {
        $entity = self::getEntity();
        $entity = new $entity();
        foreach ($primary as $key => $val) {
            $method = 'set'.ucfirst($key);
            $entity->$method($val);
        }

        return $entity;
    }

    /**
     * returns entity from $primary.
     *
     * @param $primary
     *
     * @throws EntityContainerInvalidPrimaryKeyException
     */
    public static function get($primary)
    {
        if (!is_array($primary)) {
            $entity = self::getEntity();
            $primaries = $entity::getPrimary();
            $primary = array($primaries[0] => $primary);
        }
        if (self::isCorrectPrimary($primary)) {
            $table = self::getTable();
            $primaryEntity = self::primaryToEntity($primary);
            $cache = self::getCache();
            $cachedEntity = $cache->get($primaryEntity);
            if ($cachedEntity == null) {
                return self::rowToEntity(self::getDBConnection()->get($table, '*', array('AND' => $primary)));
            }

            return $cachedEntity;
        }

        return;
    }

    /**
     * returns entity container from filter or returns all db-entries if no filter has been setted.
     *
     * @param Filter $filter
     *
     * @return EntityContainer
     */
    public static function getAll(Filter $filter = null)
    {
        $cache = self::getCache();
        $getAll = self::alreadyGetAll();
        if ($getAll && $filter == null) {
            return new static($cache->getAll());
        }
        if ($filter != null) {
            $where = $filter->get();
        }
        $table = self::getTable();
        if (!isset($where)) {
            $entities = self::rowsToEntities(self::getDBConnection()->select($table, '*'));
        } else {
            $entities = self::rowsToEntities(self::getDBConnection()->select($table, '*', $where));
        }
        foreach ($entities as $entity) {
            $cache->add($entity);
        }
        if ($filter == null) {
            $getAll = true;
        }

        return new static($entities);
    }

    /**
     * creates a paginator object for creating a pagination
     *
     * @param int $items_per_page
     * @param int $current_page
     * @param Filter $filter
     * @return Paginator
     */
    public static function paginate($items_per_page, $current_page, Filter $filter = null)
    {
        $total = static::count($filter);

        $paginator = new Paginator($total);
        $paginator->setMaxItems($items_per_page);
        $paginator->setCurrentPage($current_page);

        if($filter == null){
            $filter = new Filter();
        }
        $filter->limit($paginator->getFrom(), $paginator->getTo());
        $paginator->setItems(static::getAll($filter)->toArray());

        return $paginator;
    }

    /**
     * returns the number of results (records)
     *
     * @param Filter $filter
     *
     * @return int
     */
    public static function count(Filter $filter = null)
    {
        if(self::alreadyGetAll() && $filter == null)
        {
            $cache = self::getCache();
            $items = $cache->getAll();;
            return count($items);
        }
        if($filter != null)
        {
            $where = $filter->get();
        }
        $table = self::getTable();
        if(isset($where)){
            return self::getDBConnection()->count($table, $where);
        } else {
            return self::getDBConnection()->count($table);
        }
        return 0;
    }

    /**
     * returns if $primary is correct.
     *
     * @param array $primary
     *
     * @return bool
     *
     * @throws EntityContainerInvalidPrimaryKeyException
     */
    protected static function isCorrectPrimary(array $primary)
    {
        $class = self::getEntity();
        $pks = $class::getPrimary();
        $diff = array_diff(array_keys($primary), $pks);
        if (!empty($diff)) {
            throw new EntityContainerInvalidPrimaryKeyException($class);
        }

        return true;
    }

    /**
     * converts db result array to entity array.
     *
     * @param $rows
     *
     * @return array
     */
    protected static function rowsToEntities($rows)
    {
        $entities = array();
        foreach ($rows as $row) {
            $entities[] = self::rowToEntity($row);
        }

        return $entities;
    }

    /**
     * converts db result to entity.
     *
     * @param $row
     *
     * @return mixed
     */
    protected static function rowToEntity($row)
    {
        $class = self::getEntity();
        $entity = new $class();
        if (empty($row)) {
            return $entity;
        }
        foreach ($row as $key => $val) {
            $entity->setAttribute($key, $val);
        }
        $entity->resetModified();

        return $entity;
    }

    /**
     * returns as EntityContainer as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->entities;
    }

    /**
     * rewind for iterator.
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * current for iterator.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->entities[$this->position];
    }

    /**
     * key for iterator.
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * next for iterator.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * valid for iterator.
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->entities[$this->position]);
    }

    /**
     * returns size of entity container.
     *
     * @return int
     */
    public function size()
    {
        return count($this->entities);
    }

    /**
     * returns cache of entity container.
     *
     * @return Cache
     */
    public static function getCache()
    {
        if (!isset(static::$cache)) {
            static::$cache = new Cache();
        }

        return static::$cache;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->entities[] = $value;
        } else {
            $this->entities[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->entities[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->entities[$offset]) ? $this->entities[$offset] : null;
    }
}

class EntityContainerInvalidPrimaryKeyException extends Exception
{
}
