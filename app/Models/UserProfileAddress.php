<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserProfile;

class UserProfileAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'country',
        'country_code',
        'city',
        'econt_city_id',
        'quarter',
        'post_code',
        'street',
        'street_number',
        'building_number',
        'entrance',
        'floor',
        'apartment',
        'primary',
        'note'
    ];

    public function profile() {
        return $this->belongsTo(UserProfile::class);
    }
}
