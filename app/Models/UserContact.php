<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserContact extends Pivot
{
    public $primaryKey = "contact_id";

    protected $table = 'user_contact';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
