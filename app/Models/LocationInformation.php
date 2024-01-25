<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
