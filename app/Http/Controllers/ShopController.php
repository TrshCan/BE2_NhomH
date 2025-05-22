<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $brandSlug = null, $categorySlug = null)
    {
        $categories = Category::all();
        $brands = Brand::all();

        // Lọc sản phẩm qua scope
        $products = Product::filter($brandSlug, $categorySlug)->paginate(9);

        return view('clients.pages.shop', compact('categories', 'brands', 'products', 'brandSlug', 'categorySlug'));
    }
}
