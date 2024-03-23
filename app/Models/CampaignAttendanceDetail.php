<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CampaignAttendance;

class CampaignAttendanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'caps_handover',
        'bottles_handover',
        'cans_handover',
        'buying_consumables',
        'campaign_labour',
        'note'
    ];

    public function attendance() {
        return $this->belongsTo(CampaignAttendance::class);
    }
}
