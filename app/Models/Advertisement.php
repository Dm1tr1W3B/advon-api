<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $spatial = ['latitude', 'longitude'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'user_id',
        'company_id',
        'category_id',
        'child_category_id',
        'title',
        'description',
        'price',
        'currency_id',
        'price_type',
        'payment',
        'hashtags',
        'reach_audience',
        'travel_abroad',
        'ready_for_political_advertising',
        'photo_report',
        'make_and_place_advertising',
        'amount',
        'length',
        'width',
        'video',
        'sample',
        'deadline_date',
        'link_page',
        'attendance',
        'date_of_the',
        'date_start',
        'date_finish',
        'is_published',
        'published_at',
        'video_url',
        'is_hide',
        'country',
        'region',
        'city',
        'country_ext_code',
        'region_ext_code',
        'city_ext_code',
        'latitude',
        'longitude',
        'views_total',
        'views_today',
        'photo_id',
        'views_contact',
        'is_allocate_at',
        'is_top_country_at',
        'is_urgent_at',
        'is_moderate',
        'top_country_residue_days',
        'allocate_residue_days',
        'urgent_residue_days',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hashtags' => 'array',
    ];

    public function images()
    {
        return $this->belongsToMany(Image::class, 'advertisement_images')->using(AdvertisementImage::class)->withPivot('image_id');
    }

    public function photo()
    {
        return $this->belongsTo(Image::class, 'photo_id');
    }

    /**
     * @return bool|int|null
     */
    public function delete()
    {

        if ($this->forceDeleting) {
            return Image::destroy($this->images->map(
                function ($item) {
                    return $item->id;
                }
            ));
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,  'company_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,  'category_id');
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
