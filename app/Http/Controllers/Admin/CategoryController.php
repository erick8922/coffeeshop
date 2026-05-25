<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ═══════════════════════════════════
    //  LIST ALL CATEGORIES
    // ═══════════════════════════════════
    public function index()
    {
        $categories = DB::select('
            SELECT c.id, c.name, c.slug, c.description, c.is_active,
                c.created_at, c.updated_at,
                COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id, c.name, c.slug, c.description,
                    c.is_active, c.created_at, c.updated_at
            ORDER BY c.created_at DESC
        ');

        return view('admin.categories.index', compact('categories'));
    }

    // ═══════════════════════════════════
    //  SHOW CREATE FORM
    // ═══════════════════════════════════
    public function create()
    {
        return view('admin.categories.create');
    }

    // ═══════════════════════════════════
    //  SAVE NEW CATEGORY
    // ═══════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $slug = Str::slug($request->name);

        // Check kung may existing slug
        $existing = DB::selectOne('
            SELECT * FROM categories WHERE slug = ?
        ', [$slug]);

        if ($existing) {
            $slug = $slug . '-' . time();
        }

        DB::insert('
            INSERT INTO categories
            (name, slug, description, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ', [
            $request->name,
            $slug,
            $request->description,
            $request->boolean('is_active', true) ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!'
        ]);
    }

    // ═══════════════════════════════════
    //  SHOW EDIT FORM
    // ═══════════════════════════════════
    public function edit($id)
    {
        $category = DB::selectOne('
            SELECT * FROM categories WHERE id = ?
        ', [$id]);

        if (!$category) abort(404);

        return view('admin.categories.edit', compact('category'));
    }

    // ═══════════════════════════════════
    //  UPDATE CATEGORY
    // ═══════════════════════════════════
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $slug = Str::slug($request->name);

        DB::update('
            UPDATE categories
            SET name = ?, slug = ?, description = ?,
                is_active = ?, updated_at = NOW()
            WHERE id = ?
        ', [
            $request->name,
            $slug,
            $request->description,
            $request->boolean('is_active', true) ? 1 : 0,
            $id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!'
        ]);
    }

    // ═══════════════════════════════════
    //  DELETE CATEGORY
    // ═══════════════════════════════════
    public function destroy($id)
    {
        // Check kung may products
        $productCount = DB::selectOne('
            SELECT COUNT(*) as total FROM products WHERE category_id = ?
        ', [$id]);

        if ($productCount->total > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete — this category has ' . $productCount->total . ' product(s)!'
            ], 400);
        }

        DB::delete('DELETE FROM categories WHERE id = ?', [$id]);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }
}