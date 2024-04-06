<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\{
    User,
    OrderAddress,
    Product
};

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'amount',
        'payment_status',
        'payment_type',
        'payment_access_token',
        'payment_token_expires_at',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')->withPivot('quantity');
    }

    public function address() {
        return $this->hasOne(OrderAddress::class);
    }

    /**
     * Generate a unique order number
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        $orderNumber = Str::upper('ord-' . uniqid());

        // Ensure order number is unique
        while (self::where('number', $orderNumber)->exists()) {
            $orderNumber = Str::upper('ord-' . uniqid());
        }

        return $orderNumber;
    }
}
