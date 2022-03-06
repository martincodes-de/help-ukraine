<?php

namespace App\Http\Controllers;

use App\Models\OfferReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferReportController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "offer_id" => "exists:offers,id|required",
            "reason" => "string|required",
            "decision" => "string|nullable",
            "solved_at" => "date|nullable"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "validation-error",
                "errors" => $validator->errors()->all()
            ]);
        }

        OfferReport::create([
            "offer_id" => $request->get("offer_id"),
            "reason" => $request->get("reason"),
        ]);

        return response()->json([
            "status" => "ok",
            "alertMsg" => "Offer successfully reported. The moderation will handle the report soon.",
            "alertClass" => "alert-success",
        ]);
    }
}
