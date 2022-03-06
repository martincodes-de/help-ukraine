<?php

use App\Http\Controllers\OfferCategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferReportController;
use App\Models\OfferCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("offer-category")->group(function () {
    Route::get("/all", [OfferCategoryController::class, "listAll"]);
});

Route::prefix("offer")->group(function () {
    Route::post("report", [OfferReportController::class, "create"]);

    Route::get("all", [OfferController::class, "listAll"]);
    Route::get("all-reviewed", [OfferController::class, "listAllReviewed"]);
    Route::post("create", [OfferController::class, "create"]);
});
