<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    User,
    Location
};

class LocationInformation extends Model
{
    use HasFactory;

    protected $table = 'location_information';

    protected $fillable = [
        'location_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'working_time'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }
}
