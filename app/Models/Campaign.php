<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CampaignAttendance;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'date'
    ];

    public function attendance() {
        return $this->hasMany(CampaignAttendance::class);
    }
}
