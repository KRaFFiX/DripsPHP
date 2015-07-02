<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:46.
 */
namespace DripsPHP\HTML\Inputs;

/**
 * Class Hidden.
 *
 * represents a hidden field as php object
 */
class Hidden extends Text
{
    /**
     * used for print hidden field.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'hidden';

        return parent::__toString();
    }
}
