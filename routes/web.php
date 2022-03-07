<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.homepage');
})->name("home");

Route::get("/map", function () {
    return view("frontend.map");
})->name("map");

Route::prefix("moderation")->group(function () {
    Route::get("offer/all", [OfferController::class, "showModerationList"])->name("moderation-all-offers");
    Route::get("offer/edit/{id}", [OfferController::class, "showModerationEdit"])->name("moderation-edit");
    Route::patch("offer/edit/{id}", [OfferController::class, "editViaModeration"]);
});
