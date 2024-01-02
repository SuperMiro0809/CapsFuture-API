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
        //
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
