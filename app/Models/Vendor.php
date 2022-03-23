<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'description',
        'address',
        'email',
        'city',
        'latitude',
        'longitude',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function services()
    {
        return $this->hasMany('App\Models\Service');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function hours()
    {
        return $this->hasMany('App\Models\Hour');
    }
}
