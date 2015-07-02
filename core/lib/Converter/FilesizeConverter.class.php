<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 29.03.15 - 08:04.
 */
namespace DripsPHP\Converter;

/**
 * Class FilesizeConverter.
 *
 * used for converting filesize units
 */
class FilesizeConverter implements IConverter
{
    protected static $units = array(
        'bit' => 0.125,
        'byte' => 1,
        'kb' => 1000,
        'kib' => 1024,
        'mb' => 1000000,
        'mib' => 1048576,
        'gb' => 1000000000,
        'gib' => 1073741824,
        'tb' => 1000000000000,
        'tib' => 1099511627776,
        'pb' => 1000000000000000,
        'pib' => 1125899906842624,
        'eb' => 1000000000000000000,
        'eib' => 1152921504606846976,
        'zb' => 1000000000000000000000,
        'zib' => 1180591620717411303424,
        'yb' => 1000000000000000000000000,
        'yib' => 1208925819614629174706176,
    );

    /**
     * converts FileSize unit to another unit.
     *
     * @param float  $value
     * @param string $fromUnit
     * @param string $toUnit
     *
     * @return float
     *
     * @throws UnitNotFoundException
     */
    public static function convert($value, $fromUnit, $toUnit)
    {
        $fromUnit = strtolower($fromUnit);
        $toUnit = strtolower($toUnit);

        if (!array_key_exists($fromUnit, self::$units)) {
            throw new UnitNotFoundException();
        }
        if (!array_key_exists($toUnit, self::$units)) {
            throw new UnitNotFoundException();
        }

        $result = $value * (self::$units[$fromUnit] / self::$units[$toUnit]);

        return $result;
    }
}
