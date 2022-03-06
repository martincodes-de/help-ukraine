<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferReport extends Model
{
    use HasFactory;

    protected $fillable = [
        "offer_id",
        "reason",
        "decision",
        "solved_at",
    ];

    protected $attributes = [
        "reason" => null,
        "solved_at" => null,
    ];
}
