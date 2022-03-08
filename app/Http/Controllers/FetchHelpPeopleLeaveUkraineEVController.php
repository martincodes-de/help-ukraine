<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Services\FetchExternalOfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchHelpPeopleLeaveUkraineEVController extends Controller
{
    private string $url = "https://helppeopleleaveukraine.org/wp-json/wp/v2/job_listing";

    public function fetch()
    {
        $fetchService = new FetchExternalOfferService();

        $firstApiResponse = Http::get($this->url, [
            "_fields" => "id,link,title,status,content,metas",
            "per_page" => 100,
        ]);

        $totalPages = $firstApiResponse->header("x-wp-totalpages");
        #dump($totalPages);

        for ($page = 1; $page <= $totalPages; $page++) {
            $apiResponse = Http::get($this->url, [
                "_fields" => "id,link,title,status,content,metas",
                "per_page" => 100,
                "page" => $page,
            ]);

            $fetchService->addToDataBase($apiResponse->json());
        }
    }
}
