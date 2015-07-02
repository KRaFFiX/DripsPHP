<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 16:36.
 */
namespace DripsPHP\HTML\Inputs;

/**
 * Class Radio.
 *
 * represents a radio button (like a checkbox)
 */
class Radio extends Checkbox
{
    /**
     * used for print radio button.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'radio';

        return parent::__toString();
    }
}
