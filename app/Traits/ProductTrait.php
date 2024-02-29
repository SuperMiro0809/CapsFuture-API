<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

trait ProductTrait {
    public function getProducts($lang, $id=null, $all=false, $latest=false, $slug=null) {
        $query = Product::select(
                    'products.*',
                    'translations.title',
                    'translations.short_description',
                    'translations.description'
                )
                ->with(['files', 'translations'])
                ->leftJoin('translations', function ($q) use ($lang) {
                    $q->on('translations.parent_id', 'products.id')
                    ->where('translations.model', Product::class)
                    ->where('translations.language', $lang);
                });

        if(request()->query('title')) {
            $query->where('translations.title', 'LIKE', '%'.request()->query('title').'%');
        }

        if(request()->query('short_description')) {
            $query->where('translations.short_description', 'LIKE', '%'.request()->query('short_description').'%');
        }

        if(request()->query('description')) {
            $query->where('translations.description', 'LIKE', '%'.request()->query('description').'%');
        }

        if(request()->query('active')) {
            $query->where('active', request()->query('active'));
        }

        if(request()->query('search')) {
            $query->where(function ($q) {
                $q->where('translations.title', 'LIKE', '%'.request()->query('search').'%')
                    ->orWhere('translations.short_description', 'LIKE', '%'.request()->query('search').'%');
            });
        }

        if(request()->has(['field', 'direction'])){
            $query->orderBy(request()->query('field'), request()->query('direction'));
        }

        if($id) {
            $products = $query->where('products.id', $id)->first();
        }else if($slug) {
            $products = $query->where('products.slug', $slug)->first();
        }else if($latest) {
            $products = $query->orderBy('created_at', 'desc')->limit(4)->get();
        }else if($all) {
            $products = $query->get();
        }else {
            if(request()->query('limit')) {
                $products = $query->paginate(request()->query('limit'))->withQueryString();
            }else {
                $products = $query->paginate(10)->withQueryString();
            }
        }

        return $products;
    }
}