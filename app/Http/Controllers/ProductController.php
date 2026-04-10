<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // ═══════════════════════════════════
    //  HOME PAGE
    // ═══════════════════════════════════
    public function home()
    {
        $featured = Product::where('is_available', true)
                           ->latest()
                           ->take(6)
                           ->get();

        $categories = Category::where('is_active', true)->get();

        return view('customer.home', compact('featured', 'categories'));
    }

    // ═══════════════════════════════════
    //  MENU PAGE (lahat ng products)
    // ═══════════════════════════════════
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $products = Product::where('is_available', true)
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->with('category')
            ->paginate(12);

        return view('customer.menu', compact('products', 'categories'));
    }

    // ═══════════════════════════════════
    //  SINGLE PRODUCT PAGE
    // ═══════════════════════════════════
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('is_available', true)
                          ->with(['category', 'reviews.user'])
                          ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
                          ->where('id', '!=', $product->id)
                          ->where('is_available', true)
                          ->take(4)
                          ->get();

        return view('customer.product', compact('product', 'related'));
    }
}