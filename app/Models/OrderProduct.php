<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderProduct extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id')->withPivot('quantity');
    }
}
