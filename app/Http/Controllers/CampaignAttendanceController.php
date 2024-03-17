<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
