<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bonus_type_id',
        'amount',
        'currency_id',
        'is_active',
        'is_real_balance',
    ];

    public function bonus_type()
    {
        return $this->belongsTo(BonusType::class, 'bonus_type_id');
    }
}
