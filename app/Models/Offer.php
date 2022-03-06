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
        "visible_until",
        "reviewed",
    ];

    protected $attributes = [
        "reviewed" => null
    ];

    protected $dates = [
        "reviewed",
        "visible_until"
    ];

    public function category() {
        return $this->belongsTo(OfferCategory::class, "offer_category_id", "id");
    }

    public function reports()
    {
        return $this->hasMany(OfferReport::class);
    }
}
