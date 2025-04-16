<?php


namespace App\Http\Enums;


class ChatTypeEnum
{
    public const Private = 'private';
    public const Company = 'company';

    public const types=[
        self::Private,
        self::Company
    ];
}
