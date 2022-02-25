<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'open',
        'close',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
