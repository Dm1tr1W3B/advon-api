<?php

namespace App\Http\Enums;

use phpDocumentor\Reflection\Types\Self_;

class AdvertisementPaymentEnum
{
    public const NONE = '';
    public const DAY = 'in a day';
    public const WEEK = 'in week';
    public const MONTH = 'per month';
    public const YEAR = 'in year';
    public const FOR_20_YEARS = 'for 20 years';
    public const FOREVER = 'forever';

    public const ALL = [self::NONE, self::DAY, self::WEEK, self::MONTH, self::YEAR, self::FOR_20_YEARS, self::FOREVER];

}
