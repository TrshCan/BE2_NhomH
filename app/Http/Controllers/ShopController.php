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

        // Initialize the product query
        $query = Product::query();

        // If a brand slug is provided, filter products by that brand
        if ($brandSlug) {
            $brand = Brand::where('slug', $brandSlug)->firstOrFail();
            $query->where('brand_id', $brand->id);
        }

        // If a category slug is provided, filter products by that category
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $query->where('category_id', $category->category_id);
        }

        // Paginate the filtered products
        $products = $query->paginate(9);

        return view('clients.pages.shop', compact('categories', 'brands', 'products', 'brandSlug', 'categorySlug'));
    }
}