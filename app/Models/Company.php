<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Company extends Model
{
    use SoftDeletes;

    protected $spatial = ['latitude', 'longitude'];
    protected $fillable = [
        'name',
        'photo_id',
        'email',
        'description',
        'latitude',
        'longitude',
        'hashtags',
        'phone',
        'country',
        'region',
        'city',
        'country_id',
        'region_id',
        'city_id',
        'owner_id',
        'document_url',
        'site_url',
        'video_url',
        'audio_url',
        'logo_id',
        'is_top_at',
        'is_allocate_at',
        'is_verification',
        'top_residue_days',
        'allocate_residue_days',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany(ContactType::class, 'company_contact', 'company_id', 'contact_id')->where('status', true)->withPivot('values');
    }
    /**
     * @return BelongsToMany
     */
    public function social()
    {
        return $this->belongsToMany(SocialMediaType::class, 'company_social', 'company_id', 'social_id')->where('status', true)->withPivot('values');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class, 'company_images')->using(CompanyImage::class)->withPivot('image_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'photo_id');
    }

    public function logo()
    {
        return $this->belongsTo(Image::class, 'logo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function getLocation($column)
    {
        $model = self::select(DB::raw($column . ' AS ' . $column))
            ->where('id', $this->id)
            ->first();

        return isset($model) ? $model->$column : '';
    }

    public function getCoordinates()
    {
        $coords = [];

        if (!empty($this->spatial)) {
            $i = 0;
            foreach ($this->spatial as $column) {

                $clear = trim(preg_replace('/[a-zA-Z\(\)]/', '', $this->getLocation($column)));
                if ($i == 0) {
                    $coords[] = [
                        'lat' => $clear,
                    ];
                }
                if ($i == 1) {
                    $coords[0]['lng'] = $clear;
                }
                $i++;
            }
        }

        return $coords;
    }

}
