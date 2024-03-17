<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Campaign,
    User
};

class CampaignAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone'
    ];

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
