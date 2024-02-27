<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    CampaignAttendance,
    CampaignCity,
    Translation
};

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_image_path',
        'date'
    ];

    public function attendances() {
        return $this->hasMany(CampaignAttendance::class);
    }

    public function cities() {
        return $this->hasMany(CampaignCity::class);
    }

    public function translations() {
        return $this->morphMany(Translation::class, 'parent', 'model');
    }
}
