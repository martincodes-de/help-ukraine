<?php

namespace App\Http\Controllers;

use App\Models\OfferCategory;
use Illuminate\Http\Request;

class OfferCategoryController extends Controller
{
    public function listAll(Request $request)
    {
        $categories = OfferCategory::all();

        $controlObj = [];

        foreach ($categories as $category) {
            $controlObj[$category->id] = [
                "name" => $category->name,
                "marker" => []
            ];
        }

        return response()->json([
            "categories" => $categories,
            "controlObject" => $controlObj
        ]);
    }
}
