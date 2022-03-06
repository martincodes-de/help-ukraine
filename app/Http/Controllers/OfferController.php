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
        "visible_until" => "required|date|after_or_equal:today",
        "offer_category_id" => "required|exists:offer_categories,id",
    ];

    public function listAll(Request $request) {
       $offers = Offer::with("category")->get(["id", "name", "description", "contact", "lat", "lng", "offer_category_id"]);

        return response()->json([
            "status" => "ok",
            "offers" => $offers
        ]);
    }

    public function listAllReviewed(Request $request) {
        $offers = Offer::with("category")
                    ->where([
                        ["reviewed", "!=", null],
                        ["visible_until", ">=", date("Y.m.d")]
                    ])
                    ->get(["id", "name", "description", "contact", "lat", "lng", "offer_category_id"]);

        return response()->json([
            "status" => "ok",
            "offers" => $offers
        ]);
    }

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
        $offer->visible_until = $request->get("visible_until");
        $offer->lat = $request->get("lat");
        $offer->lng = $request->get("lng");

        $offer->save();

        return response()->json([
            "status" => "ok",
            "offer" => $offer,
        ]);
    }

    public function showModerationList(Request $request) {
        return view("moderation.offer.index", [
            "offers" => Offer::all()->sortByDesc("created_at")
        ]);
    }
}
