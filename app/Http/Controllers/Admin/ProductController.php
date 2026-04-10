<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ═══════════════════════════════════
    //  LIST NG LAHAT NG PRODUCTS
    // ═══════════════════════════════════
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // ═══════════════════════════════════
    //  SHOW CREATE FORM
    // ═══════════════════════════════════
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    // ═══════════════════════════════════
    //  SAVE BAGONG PRODUCT
    // ═══════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'size_options' => 'nullable|array',
            'is_available' => 'boolean',
        ]);

        // I-upload ang image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imagePath,
            'size_options' => json_encode($request->size_options ?? []),
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated!');
    }

    // ═══════════════════════════════════
    //  SHOW EDIT FORM
    // ═══════════════════════════════════
    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // ═══════════════════════════════════
    //  UPDATE PRODUCT
    // ═══════════════════════════════════
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'size_options' => 'nullable|array',
            'is_available' => 'boolean',
        ]);

        // I-upload ang bagong image kung meron
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'size_options' => json_encode($request->size_options ?? []),
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated!');
    }

    // ═══════════════════════════════════
    //  DELETE PRODUCT
    // ═══════════════════════════════════
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted!');
    }
}