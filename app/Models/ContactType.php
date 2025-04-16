<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    use HasFactory;
    protected $fillable=["name","status", "image_id"];
    protected $table='contact_types';
}
