<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // Adjusted to singular 'Category' per Laravel convention
use App\Models\Promotion; // Assuming you have a Promotion model
use App\Models\Product;   // Assuming you have a Product model

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories
        $categories = Category::all();

        // Fetch all promotions for the carousel
        $promotions = Promotion::all();

        // Fetch products, filter by category_id if provided
        $categoryId = $request->query('category_id');
        $products = $categoryId
            ? Product::where('category_id', $categoryId)->get()
            : Product::all();

        // Return the view with all data
        return view('clients.pages.home', compact('categories', 'promotions', 'products'));
    }
}
