<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category; // Adjusted to singular 'Category' per Laravel convention
use App\Models\Promotion; // Assuming you have a Promotion model
use App\Models\Product;   // Assuming you have a Product model

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $products = Product::paginate(2);
        return view('clients.pages.shop', compact('categories', 'brands', 'products'));
    }
}
