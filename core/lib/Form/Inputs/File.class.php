<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 21.02.15 - 17:56.
 */
namespace DripsPHP\Form\Inputs;

/**
 * Class File.
 *
 * used for file inputs (upload)
 */
class File extends Text
{
    /**
     * used for print file field.
     *
     * @return string
     */
    public function __toString()
    {
        $this->attributes['type'] = 'file';
        $this->saveValue = false;

        return parent::__toString();
    }

    public function isValid()
    {
        return true;
    }
}
