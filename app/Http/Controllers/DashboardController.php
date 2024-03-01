<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Campaign,
    Post,
    Product,
    Location
};

class DashboardController extends Controller
{
    public function index()
    {
        $campaignCount = Campaign::count();

        $postCount = Post::count();

        $productCount = Product::count();

        $locationCount = Location::count();

        return response()->json([
            'campaignCount' => $campaignCount,
            'postCount' => $postCount,
            'productCount' => $productCount,
            'locationCount' => $locationCount
        ]);
    }
}
