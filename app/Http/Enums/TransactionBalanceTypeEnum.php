<?php

namespace App\Http\Enums;

class TransactionBalanceTypeEnum
{
    public const DEPOSIT = 1;
    public const COMPANY_TOP = 2;
    public const COMPANY_ALLOCATE = 3;
    public const ADVERTISEMENT_TOP_COUNTRY = 4;
    public const ADVERTISEMENT_ALLOCATE = 5;
    public const ADVERTISEMENT_URGENT = 6;
    public const ADVERTISEMENT_TURBO = 7;
    public const INCREASE_LIMIT_ADVERTISEMENT_CATEGORY = 8;
    public const ADMIN_CHANGE_BALANCE = 9;
    public const ADMIN_BONUS_REGISTRATION_REAL = 11;
    public const ADMIN_BONUS_REFERRAL_REAL = 12;
    public const INCREASE_LIMIT_VIEW_CONTACT_USER = 13;


    public const REPLENISHMENT_FROM_BONUS_BALANCE = 10;
    public const WITHDRAWAL_FROM_BONUS_BALANCE = 101;

    public const BONUS_REGISTRATION = 102;
    public const BONUS_REFERRAL = 103;
    public const BONUS_REGISTRATION_REAL = 202;
    public const BONUS_REFERRAL_REAL = 203;

    public const KEYS = [
        self::DEPOSIT => 'balance_type_deposit',
        self::COMPANY_TOP => 'balance_type_company_top',
        self::COMPANY_ALLOCATE => 'balance_type_company_allocate',
        self::ADVERTISEMENT_TOP_COUNTRY => 'balance_type_advertisement_top_country',
        self::ADVERTISEMENT_ALLOCATE => 'balance_type_advertisement_allocate',
        self::ADVERTISEMENT_URGENT => 'balance_type_advertisement_urgent',
        self::ADVERTISEMENT_TURBO => 'balance_type_advertisement_turbo',
        self::INCREASE_LIMIT_ADVERTISEMENT_CATEGORY => 'balance_type_increase_limit_advertisement_category',
        self::ADMIN_CHANGE_BALANCE => 'balance_type_admin_change_balance',
        self::REPLENISHMENT_FROM_BONUS_BALANCE => 'replenishment_from_bonus_balance',
        self::WITHDRAWAL_FROM_BONUS_BALANCE => 'withdrawal_from_bonus_balance',
        self::BONUS_REGISTRATION => 'bonus_registration',
        self::BONUS_REFERRAL => 'bonus_referral',
        self::BONUS_REGISTRATION_REAL => 'bonus_registration_real',
        self::BONUS_REFERRAL_REAL => 'bonus_referral_real',
        self::ADMIN_BONUS_REGISTRATION_REAL => 'admin_bonus_registration_real',
        self::ADMIN_BONUS_REFERRAL_REAL => 'admin_bonus_referral_real',
        self::INCREASE_LIMIT_VIEW_CONTACT_USER => 'increase_limit_view_contact_user',
    ];

}
