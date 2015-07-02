<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:00.
 */
namespace DripsPHP\HTML\Inputs;

/**
 * Class Range.
 *
 * represents input type range
 */
class Range extends Text
{
    /**
     * used for print range field.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'range';

        return parent::__toString();
    }
}
