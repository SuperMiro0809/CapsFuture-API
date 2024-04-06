<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderAddress extends Model
{
    use HasFactory;

    protected $table = 'order_address';

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
        'note'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
