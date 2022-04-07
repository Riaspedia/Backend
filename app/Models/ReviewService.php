<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewService extends Model
{
    use HasFactory;

    protected $table = "review_service";
    protected $fillable = ["service_id"];
    public $timestamps = false;
}
