<?php

namespace App\Http\Enums;

class PriceTypeEnum
{
    public const NO_BARGAINING = 'no_bargaining'; // торг не возможен выбран
    public const BARGAINING = 'bargaining'; // торг возможен выбран
    public const NEGOTIABLE = 'negotiable'; // Договорная
    public const NO_BARGAINING_ID = 0; // торг не возможен выбран
    public const BARGAINING_ID = 1; // торг возможен выбран
    public const NEGOTIABLE_ID = 8; // Договорная

    public const ALL = [
        self::NO_BARGAINING_ID => self::NO_BARGAINING,
        self::BARGAINING_ID => self::BARGAINING,
        self::NEGOTIABLE_ID => self::NEGOTIABLE
    ];

}
