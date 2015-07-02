<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 27.02.15 - 07:19.
 */
namespace DripsPHP\Security;

/**
 * Class Hash.
 *
 * This class is used for creating hashes
 */
class Hash
{
    public static $algorithm = 'sha512';
    public static $salt = '';

    /**
     * checks if the given algorithm does exist.
     *
     * @return bool
     */
    public static function algorithmExists($algorithm = null)
    {
        if ($algorithm === null) {
            $algorithm = self::$algorithm;
        }

        return in_array($algorithm, hash_algos());
    }

    /**
     * generates a hash.
     *
     * @param $string
     * @param $algorithm
     *
     * @return string
     */
    public static function generate($string, $algorithm = null)
    {
        if ($algorithm === null) {
            $algorithm = self::$algorithm;
        }
        if (self::algorithmExists($algorithm)) {
            return hash($algorithm, $string);
        }

        return '';
    }

    /**
     * encrypts a string with a salt.
     *
     * @param $string
     * @param null $salt
     *
     * @return string
     */
    public static function encrypt($string, $salt = null)
    {
        if ($salt === null) {
            $salt = self::$salt;
        }

        return crypt($string, $salt);
    }

    /**
     * generates a random hash.
     *
     * @param string $type
     *
     * @return string
     */
    public static function random($type = 'md5')
    {
        return hash($type, uniqid(time()));
    }
}
