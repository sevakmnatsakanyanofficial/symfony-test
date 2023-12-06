<?php

namespace App\Helper;

/**
 * The helper is to help to work with country taxes
 * Here are list of patterns of tax numbers:
 *   DEXXXXXXXXX - for Germany,
 *   ITXXXXXXXXXXX - for Italy,
 *   GRXXXXXXXXX - for Greece,
 *   FRYYXXXXXXXXX - for France
 *
 *   where X is any number , Y is any letter
 */
class TaxHelper
{
    public const GERMANY_NUMBER_FORMAT_PATTERN = '/^DE\d{9}$/';
    public const ITALY_NUMBER_FORMAT_PATTERN = '/^IT\d{11}$/';
    public const GREECE_NUMBER_FORMAT_PATTERN = '/^GR\d{9}$/';
    public const FRANCE_NUMBER_FORMAT_PATTERN = '/^FR[A-Z]{2}\d{9}$/';


    public static function getPercentValueByNumber(string $taxNumber): int
    {
        if (preg_match(self::GERMANY_NUMBER_FORMAT_PATTERN, $taxNumber)) {
            return 19;
        } elseif (preg_match(self::ITALY_NUMBER_FORMAT_PATTERN, $taxNumber)) {
            return 22;
        } elseif (preg_match(self::GREECE_NUMBER_FORMAT_PATTERN, $taxNumber)) {
            return 24;
        } elseif (preg_match(self::FRANCE_NUMBER_FORMAT_PATTERN, $taxNumber)) {
            return 20;
        } else {
            return 0;
        }
    }
}
