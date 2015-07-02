<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 01.04.15 - 14:21.
 */
namespace DripsPHP\Database\ORM;

/**
 * Class Filter.
 *
 * used for creating filters for db queries in ORM
 */
class Filter
{
    protected $where = array();
    protected $order;
    protected $limit;

    /**
     * add where clause to filter.
     *
     * @param array $where
     *
     * @return $this
     */
    public function where(array $where)
    {
        $this->where = array_merge($this->where, $where);

        return $this;
    }

    /**
     * add order by to filter.
     *
     * @param $order
     *
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * add only $max results should be returned.
     *
     * @param $max
     *
     * @return $this
     */
    public function only($max = 1)
    {
        $this->limit = $max;

        return $this;
    }

    /**
     * add a limit.
     *
     * @param $from
     * @param $to
     *
     * @return $this
     */
    public function limit($from, $to)
    {
        $this->limit = array($from, $to);

        return $this;
    }

    /**
     * returns where for database select.
     *
     * @return array
     */
    public function get()
    {
        $filter = $this->where;
        if (isset($this->order)) {
            $filter['ORDER'] = $this->order;
        }
        if (isset($this->limit)) {
            $filter['LIMIT'] = $this->limit;
        }

        return $filter;
    }

    /**
     * checks if 2 filters are identical.
     *
     * @param Filter $filter1
     * @param Filter $filter2
     *
     * @return bool
     */
    public static function compare(Filter $filter1, Filter $filter2)
    {
        return $filter1->get() == $filter2->get();
    }
}
