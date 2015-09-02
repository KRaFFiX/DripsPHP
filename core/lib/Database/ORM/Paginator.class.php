<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 23.08.15 - 16:03.
 */
namespace DripsPHP\Database\ORM;

use DripsPHP\Form\Inputs\Button;
use DripsPHP\Routing\RequestHandler;
use DripsPHP\Routing\Redirect;

/**
 * Class Paginator
 *
 * manages pagination
 */
class Paginator
{
    protected $max_items = 20;
    protected $total = 0;
    protected $items = array();
    protected $page = 1;
    protected $max_numbers = 10;

    /**
     * creates a new paginator instance
     *
     * @param int $total
     */
    public function __construct($total)
    {
        $this->total = $total;
    }

    /**
     * sets the items for the pagination
     *
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * returns the items for the pagination
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * sets maximum item size per page
     *
     * @param int $items
     */
    public function setMaxItems($items)
    {
        $this->max_items = $items;
    }

    /**
     * returns the maximum item size
     *
     * @return int
     */
    public function getMaxItems()
    {
        return $this->max_items;
    }

    /**
     * returns the total amount of pages
     *
     * @return int
     */
    public function getPages()
    {
        return ceil($this->total / $this->max_items);
    }

    /**
     * sets the current page
     *
     * returns true or false on success or fail (page is valid)
     * sets page = 1 when fail
     *
     * @param int $page
     *
     * @return bool
     */
    public function setCurrentPage($page)
    {
        if($page <= $this->getPages() && $page > 0)
        {
            $this->page = $page;
            return true;
        }
        $this->page = 1;
        return false;
    }

    /**
     * returns the current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * returns from-statement for sql-query
     *
     * @return int
     */
    public function getFrom()
    {
        return ($this->page - 1) * $this->max_items;
    }

    /**
     * returns to-statement for sql-query
     *
     * @return int
     */
    public function getTo()
    {
        return $this->max_items;
    }

    /**
     * returns a button object which redirects to the previous page
     *
     * @param Button $btn
     *
     * @return Button
     */
    public function getPrevBtn(Button $btn)
    {
        $route = RequestHandler::getRoute();
        $routename = $route["name"];

        $page = $this->page;
        if($page - 1 < 1)
        {
            $btn->setAttribute("disabled", "disabled");
        } else {
            $btn->setAttribute("onclick", "location.href= \"" . Redirect::link($routename, ["page" => $this->page-1]) . "\";");
        }

        return $btn;
    }

    /**
     * returns a button object which redirects to the next page
     *
     * @param Button $btn
     *
     * @return Button
     */
    public function getNextBtn(Button $btn)
    {
        $route = RequestHandler::getRoute();
        $routename = $route["name"];

        $page = $this->page;
        if($page + 1 > $this->getPages())
        {
            $btn->setAttribute("disabled", "disabled");
        } else {
            $btn->setAttribute("onclick", "location.href= \"" . Redirect::link($routename, ["page" => $this->page+1]) . "\";");
        }

        return $btn;
    }

    /**
     * sets the maxium amount of number buttons which should be created
     * needs to be a straight value => 2, 4, 6, ...
     *
     * @param int $number
     */
    public function setMaxNumbers($number)
    {
        if($number % 2 != 0){
            $number++;
        }
        $this->max_numbers = $number;
    }

    /**
     * returns the maxium amount of number buttons
     *
     * @return int
     */
    public function getMaxNumbers()
    {
        return $this->max_numbers;
    }

    /**
     * returns array with button objects for creating pagination menu
     *
     * @param Button $btn
     *
     * @return array
     */
    public function getNumberBtns(Button $btn)
    {
        $btns = array();
        $route = RequestHandler::getRoute();
        $routename = $route["name"];

        $page = $this->page;
        $numbers = $this->max_numbers;
        $half = $numbers/2;

        if($page <= $half)
        {
            $start = 1;
        } else {
            $start = ($page - 1) * $half;
        }

        $end = $start + $numbers;
        if($end > $this->getPages())
        {
            $end = $this->getPages();
        }

        if($end - ($numbers + 1) > 0)
        {
            $start = $end - ($numbers);
        }

        for($i = $start; $i <= $end; $i++)
        {
            $button = clone $btn;
            if($i == $this->page)
            {
                $button->setAttribute("disabled", "disabled");
            }
            $button->setAttribute("onclick", "location.href= \"" . Redirect::link($routename, ["page" => $i]) . "\";");
            $button->setAttribute("value", $i);
            $btns[] = $button;
        }

        return $btns;
    }
}
