<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Campaign,
    CampaignAttendance
};

trait CampaignAttendanceTrait {
    public function getAttendances($lang, $objectType=null, $objectValue=null) {
        $query = CampaignAttendance::select(
                    'campaign_attendances.*',
                )
                ->with([
                    'campaign' => function ($q) use ($lang) {
                        $q->select(
                            'campaigns.*',
                            'translations.title',
                            'translations.short_description',
                            'translations.description',
                        )
                        ->leftJoin('translations', function ($q) use ($lang) {
                            $q->on('translations.parent_id', 'campaigns.id')
                            ->where('translations.model', Campaign::class)
                            ->where('translations.language', $lang);
                        });
                    }, 
                    'campaign.cities',
                    'campaign.attendances',
                    'user'
                ]);
        
        switch ($objectType) {
            case 'user':
                $query->where('user_id', $objectValue);
                break;
            case 'campaign':
                $query->where('campaign_id', $objectValue);
                break;
        }

        // if($all) {
        //     $campaignAttendances = $query->get();
        // }else {
        //     if(request()->query('limit')) {
        //         $campaignAttendances = $query->paginate(request()->query('limit'))->withQueryString();
        //     }else {
        //         $campaignAttendances = $query->paginate(10)->withQueryString();
        //     }
        // }

        $campaignAttendances = $query->get();

        return $campaignAttendances;
    }
}