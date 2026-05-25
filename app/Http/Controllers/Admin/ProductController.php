<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
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
           $products = DB::select('
                SELECT p.*, c.name as category_name
                FROM products p
                JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC
            ');

            return view('admin.products.index', compact('products'));
    }

    // ═══════════════════════════════════
    //  SHOW CREATE FORM
    // ═══════════════════════════════════
    public function create()
    {
        $categories = DB::select('
            SELECT * FROM categories WHERE is_active = 1
        ');
        return view('admin.products.create', compact('categories'));
    }

    // ═══════════════════════════════════
    //  SAVE BAGONG PRODUCT
    // ═══════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available'=> 'boolean',
        ]);

        // I-build ang size_options
        $sizeOptions = [];
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                $price = $request->size_prices[$size] ?? null;
                if ($price) {
                    $sizeOptions[] = [
                        'size'  => $size,
                        'price' => (float) $price,
                    ];
                }
            }
        }

        // I-upload ang image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/product_images'), $filename);
            $imagePath = $filename;
        }

        Product::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imagePath,
            'size_options' => json_encode($sizeOptions),
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product created!');
    }

    // ═══════════════════════════════════
    //  SHOW EDIT FORM
    // ═══════════════════════════════════
    public function edit($id)
    {
         $product = DB::selectOne('
            SELECT p.*, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ', [$id]);

        if (!$product) abort(404);

        $categories = DB::select('
            SELECT * FROM categories WHERE is_active = 1
        ');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // ═══════════════════════════════════
    //  UPDATE PRODUCT
    // ═══════════════════════════════════
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available'=> 'boolean',
        ]);

        // I-build ang size_options
        $sizeOptions = [];
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                $price = $request->size_prices[$size] ?? null;
                if ($price) {
                    $sizeOptions[] = [
                        'size'  => $size,
                        'price' => (float) $price,
                    ];
                }
            }
        }

        // I-upload ang bagong image kung meron
        if ($request->hasFile('image')) {
            // Burahin ang lumang image
            if ($product->image) {
                $oldPath = public_path('images/product_images/' . $product->image);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $file      = $request->file('image');
            $filename  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/product_images'), $filename);
            $product->image = $filename;
        }

        $product->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'size_options' => json_encode($sizeOptions),
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated!');
    }


    // ═══════════════════════════════════
    //  DELETE PRODUCT
    // ═══════════════════════════════════
    public function destroy($id){

        $product = DB::selectOne('SELECT * FROM products WHERE id = ?', [$id]);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found!'
            ], 404);
        }

        DB::delete('DELETE FROM products WHERE id = ?', [$id]);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }
}