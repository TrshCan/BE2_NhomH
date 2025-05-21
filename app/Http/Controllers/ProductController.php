<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        $products = Product::query()
            ->byCategory($categoryId)
            ->search($search)
            ->paginate(8)
            ->withQueryString();

        $categories = Category::all();
        $carouselProducts = Product::featured()->get();
        $dealEndTime = now()->addDays(4);
        $dealOfTheWeekProduct = Product::dealOfTheWeek();
        $bestSellers = Product::bestSellers()->get();
        $latestBlogs = Blog::orderByDesc('published_at')->take(3)->get();
        $brands = Brand::withCount('products')->get();

        return view('clients.pages.home', compact(
            'products',
            'carouselProducts',
            'categories',
            'dealEndTime',
            'categoryId',
            'search',
            'dealOfTheWeekProduct',
            'bestSellers',
            'latestBlogs',
            'brands'
        ));
    }

    public function show($id)
    {
        $product = Product::findWithDetails($id);

        return view('clients.pages.product_detail', compact('product'));
    }
}
