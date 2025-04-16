<?php

namespace App\Http\Enums;

class CategoryTypeEnum
{
    public const PERFORMER = 'performer'; // исполнитель
    public const EMPLOYER = 'employer'; // работодатель  в старом промо

    public const ALL = [self::PERFORMER, self::EMPLOYER];

}
