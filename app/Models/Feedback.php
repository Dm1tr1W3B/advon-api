<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
        'feedback_type_id',
        'message',
        'was_viewed'
    ];

    public function feedback_type()
    {
        return $this->belongsTo(FeedbackType::class, 'feedback_type_id');
    }
}
