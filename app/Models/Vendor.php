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
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function hours()
    {
        return $this->hasMany(Hour::class);
    }
}
