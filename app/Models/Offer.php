<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "offer_category_id",
        "description",
        "contact",
        "lat",
        "lng",
        "reviewed"
    ];

    protected $attributes = [
        "reviewed" => null
    ];
}
