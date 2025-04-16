<?php

namespace App\Http\Enums;

class PersonTypeEnum
{
    public const NONE = 'none';
    public const PRIVATE_PERSON = 'private_person';
    public const COMPANY = 'company';

    public const ALL = [self::NONE, self::PRIVATE_PERSON, self::COMPANY];

}
