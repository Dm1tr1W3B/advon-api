<?php

namespace App\Http\Enums;

class ComplaintTypeEnum
{
    public const USER = 1;
    public const COMPANY  = 2;
    public const ADVERTISEMENT = 3;

    public const ALL = [self::USER, self::COMPANY, self::ADVERTISEMENT];
}
