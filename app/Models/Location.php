<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    LocationType,
    LocationInformation
};

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'type_id',
        'collects_caps',
        'collects_bottles',
        'collects_cans'
    ];

    public function type() {
        return $this->belongsTo(LocationType::class);
    }

    public function information() {
        return $this->hasOne(LocationInformation::class);
    }
}
