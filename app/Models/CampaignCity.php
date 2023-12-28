<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;

class CampaignCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'city'
    ];

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
