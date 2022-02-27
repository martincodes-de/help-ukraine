<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    private array $validationRules = [
        "name" => "required|string",
        "description" => "required|string",
        "contact" => "required",
        "lat" => "required",
        "lng" => "required",
        "offer_category_id" => "required|exists:offer_categories,id"
    ];

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                "status" => "validation-error",
                "errors" => $validator->errors()->all()
            ]);
        }

        $offer = new Offer();

        $offer->name = $request->get("name");
        $offer->offer_category_id = $request->get("offer_category_id");
        $offer->description = $request->get("description");
        $offer->contact = $request->get("contact");
        $offer->lat = $request->get("lat");
        $offer->lng = $request->get("lng");

        $offer->save();

        return response()->json([
            "status" => "ok",
            "offer" => $offer,
        ]);
    }
}
