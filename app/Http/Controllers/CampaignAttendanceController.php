<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignAttendance;
use App\Traits\CampaignAttendanceTrait;


class CampaignAttendanceController extends Controller
{
    use CampaignAttendanceTrait;


    public function campaignAttendances($id)
    {
        $lang = request()->query('lang', 'bg');

        $campaignAttendances = $this->getAttendances($lang, 'campaign', $id);

        return $campaignAttendances;
    }

    public function userAttendances($id)
    {
        $lang = request()->query('lang', 'bg');

        $campaignAttendances = $this->getAttendances($lang, 'user', $id);

        return $campaignAttendances;
    }

    public function destroy($id)
    {
        $campaignAttendance = CampaignAttendance::find($id);

        $campaignAttendance->details()->delete();

        $campaignAttendance->delete();

        return 'Delete successful';
    }

    public function deleteMany(Request $request)
    {
        $ids = $request->ids;

        $result = DB::transaction(function () use ($ids) {
            foreach($ids as $id) {
                $campaignAttendance = CampaignAttendance::find($id);

                $campaignAttendance->details()->delete();

                $campaignAttendance->delete();
            }

            return 'Delete successful';
        });

        return $result;
    }
}
