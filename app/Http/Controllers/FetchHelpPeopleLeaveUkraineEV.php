<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchHelpPeopleLeaveUkraineEV extends Controller
{
    private string $url = "https://helppeopleleaveukraine.org/wp-json/wp/v2/job_listing";

    public function fetch()
    {
        # Delete all created offers by HelpPeopleLeaveUkraineEV
        Offer::where([
            ["name", "like", "%Help People Leave Ukraine e.V. - %"],
            ["created_at", "<", date("Y-m-d H:i:s")]
        ])->delete();

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

            $this->addToDataBase($apiResponse->json());
        }
    }

    private function addToDataBase(array $entries)
    {
        foreach ($entries as $entry) {
            $category = reset($entry["metas"]["_job_category"]);
            $categoryIdMapping = match($category) {
                "Accommodation" => 1,
                default => 4,
            };

            $descriptionText = sprintf(
                "<i>%s</i><br><br>%s",
                $entry["title"]["rendered"],
                $entry["content"]["rendered"]
            );

            $contactText = sprintf(
                "Please visit the offer on the HelpPeopleLeaveUkraine Website <a href='%s' target='_blank'>here.</a>",
                $entry["link"]
            );

            $newOffer = [
                "name" => "Help People Leave Ukraine e.V. - ".$entry["metas"]["_job_employer_name"],
                "offer_category_id" => $categoryIdMapping,
                "description" => $descriptionText,
                "contact" => $contactText,
                "lat" => $entry["metas"]["_job_map_location"]["latitude"],
                "lng" => $entry["metas"]["_job_map_location"]["longitude"],
                "moderation_notice" => "This offer is generated automatically threw the system.",
                "reviewed" => date("Y-m-d"),
                "visible_until" => date("Y-m-d")
            ];

            #dump($newOffer);
            Offer::create($newOffer);
        }
    }
}
