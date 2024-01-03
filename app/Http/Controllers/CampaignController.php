<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Campaign,
    CampaignAttendance,
    CampaignCity,
    Translation
};

class CampaignController extends Controller
{
    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $query = Campaign::select(
                        'campaigns.*',
                        'translations.title',
                        'translations.short_description',
                        'translations.description'
                    )
                    ->with('cities')
                    ->leftJoin('translations', function ($q) use ($lang) {
                        $q->on('translations.parent_id', 'campaigns.id')
                          ->where('translations.model', Campaign::class)
                          ->where('translations.language', $lang);
                    });

        if(request()->query('total')) {
            $campaigns = $query->paginate(request()->query('total'))->withQueryString();
        }else {
            $campaigns = $query->paginate(10)->withQueryString();
        }

        return $campaigns;
    }

    public function store(Request $request)
    {
        $cities = json_decode($request->cities, true);
        $information = json_decode($request->information, true);

        $result = DB::transaction(function () use ($request, $cities, $information) {
            $campaign = Campaign::create([
                'title_image_path' => 'test',
                'date' => $request->date
            ]);

            foreach($cities as $city) {
                CampaignCity::create([
                    'campaign_id' => $campaign->id,
                    'city' => $city
                ]);
            }

            foreach($information as $key=>$info) {
                Translation::create([
                    'parent_id' => $campaign->id,
                    'model' => Campaign::class,
                    'title' => $info['title'],
                    'short_description' => $info['short_description'],
                    'description' => $info['description'],
                    'language' => $key
                ]);
            }

            return $campaign;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function show($id)
    {
        //
    }
}
