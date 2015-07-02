<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 28.01.15 - 20:36.
 */
namespace DripsPHP\MVC;

/**
 * Class Model.
 *
 * Model of the MVC-System
 */
abstract class Model
{
    /**
     * returns object-variables in JSON-Format.
     *
     * @return string
     */
    public function toJSON()
    {
        return json_encode(get_object_vars($this));
    }

    /**
     * returns var_dump of object-vars used for debugging only.
     *
     * @return string
     */
    public function __toString()
    {
        ob_start();
        var_dump(get_object_vars($this));
        $objectvars = ob_get_contents();
        ob_end_clean();

        return $objectvars;
    }
}
