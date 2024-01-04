<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\CampaignTrait;
use App\Models\{
    Campaign,
    CampaignAttendance,
    CampaignCity,
    Translation
};

class CampaignController extends Controller
{
    use CampaignTrait;

    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $campaigns = $this->getCampaigns($lang);

        return $campaigns;
    }

    public function store(Request $request)
    {
        $cities = json_decode($request->cities, true);
        $information = json_decode($request->information, true);

        $title_image = $request->file('title_image');

        $title_image_path = $title_image->store('campaigns', 'public');

        $result = DB::transaction(function () use ($request, $cities, $information, $title_image_path) {
            $campaign = Campaign::create([
                'title_image_path' => $title_image_path,
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
        $result = DB::transaction(function () use ($id) {
            $campaign = Campaign::find($id);

            $campaign->attendance()->delete();

            $campaign->cities()->delete();

            $campaign->translations()->delete();

            $campaign->delete();

            return 'Delete successful';
        });

        return $result;
    }

    public function deleteMany(Request $request)
    {
        $ids = $request->ids;

        $result = DB::transaction(function () use ($ids) {
            foreach($ids as $id) {
                $campaign = Campaign::find($id);

                $campaign->attendance()->delete();

                $campaign->cities()->delete();

                $campaign->translations()->delete();

                $campaign->delete();
            }

            return 'Delete successful';
        });

        return $result;
    }

    public function show($id)
    {
        $lang = request()->query('lang', 'bg');

        $campaign = $this->getCampaigns($lang, $id);

        return $campaign;
    }
}
