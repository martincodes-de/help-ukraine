<?php

namespace App\Services;

use App\Models\Offer;

class FetchExternalOfferService
{

    public function __construct()
    {
        # Delete all created offers by HelpPeopleLeaveUkraineEV
        Offer::where([
            ["name", "like", "%Help People Leave Ukraine e.V. - %"],
            ["created_at", "<", date("Y-m-d H:i:s")]
        ])->delete();
    }

    public function addToDataBase(array $entries)
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

            Offer::create($newOffer);
        }
    }
}
