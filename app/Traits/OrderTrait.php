<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Order,
    Product
};

trait OrderTrait {
    public function getOrders($lang, $orderNumber=null, $all=false) {
        $query = Order::select(
                    'orders.*',
                )
                ->with([
                    'user',
                    'user.profile',
                    'products' => function ($q) use ($lang) {
                        $q->select(
                            'products.*',
                            'translations.title',
                            'translations.short_description',
                            'translations.description',
                        )
                        ->leftJoin('translations', function ($qr) use ($lang) {
                            $qr->on('translations.parent_id', 'products.id')
                            ->where('translations.model', Product::class)
                            ->where('translations.language', $lang);
                        });
                    },
                    'products.files',
                    'address'
                ]);

        if($orderNumber) {
            $orders = $query->where('orders.number', $orderNumber)->first();
        }else if($all) {
            $orders = $query->get();
        }else {
            if(request()->query('limit')) {
                $orders = $query->paginate(request()->query('limit'))->withQueryString();
            }else {
                $orders = $query->paginate(10)->withQueryString();
            }
        }

        return $orders;
    }
}