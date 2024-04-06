<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{
    Order,
    OrderProduct,
    OrderAddress
};
use Carbon\Carbon;
use App\Traits\OrderTrait;

class OrderController extends Controller
{
    use OrderTrait;

    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $orders = $this->getOrders($lang, null, true);

        return $orders;
    }

    public function create(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $token = null;
            $expiresAt = null;

            if ($request->payment == 'credit') {
                $token = Str::random(40);
                $expiresAt = Carbon::now()->addMinutes(25);
            }

            $order = Order::create([
                'number' => Order::generateOrderNumber(),
                'amount' => $request->total,
                'payment_status' => 'pending',
                'payment_type' => $request->payment,
                'payment_access_token' => $token,
                'payment_token_expires_at' => $expiresAt,
                'user_id' => $request->user_id ?? null
            ]);

            $address = $request->address;

            $order->address()->create([
                'full_name' => $address['fullName'],
                'phone' => $address['phone'],
                'country' => $address['country'],
                'country_code' => $address['countryCode'],
                'city' => $address['city'],
                'econt_city_id' => $address['econtCityId'],
                'quarter' => $address['quarter'],
                'post_code' => $address['postCode'],
                'street' => $address['street'],
                'street_number' => $address['streetNumber'],
                'building_number' => $address['buildingNumber'],
                'entrance' => $address['entrance'],
                'floor' => $address['floor'],
                'apartment' => $address['apartment'],
                'note' => $address['note']
            ]);

            $productsToAttach = [];

            foreach ($request->products as $product) {
                $productsToAttach[$product['id']] = ['quantity' => $product['quantity']];
            }

            $order->products()->attach($productsToAttach);

            return $order;
        });

        return $result;
    }

    public function paymentAccess(Request $request, $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $token = $request->query('token');
        $now = Carbon::now();

        if ($order->payment_access_token === $token && $now->lessThan($order->payment_token_expires_at)) {    
            return response()->json(['message' => 'Access granted.']);
        }

        return response()->json(['message' => 'This link is invalid or has expired.'], 403);
    }

    public function updatePaymentStatus(Request $request, $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $order->update([
            'payment_status' => $request->paymentStatus
        ]);

        return $order;
    }
}
