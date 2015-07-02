<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 29.03.15 - 08:03.
 */
namespace DripsPHP\Converter;

/**
 * Class TimeConverter.
 *
 * used for converting time units
 */
class TimeConverter implements IConverter
{
    protected static $units = array(
        'ps' => 0.000000000001,
        'ns' => 0.000000001,
        'mys' => 0.000001,
        'ms' => 0.001,
        's' => 1,
        'm' => 60,
        'h' => 3600,
        'd' => 86400,
    );

    /**
     * converts Time unit to another unit.
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
