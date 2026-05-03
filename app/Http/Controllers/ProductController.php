<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // ═══════════════════════════════════
    //  HOME PAGE
    // ═══════════════════════════════════
    public function home()
    {
        $featured = DB::select('
            SELECT p.*, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.is_available = 1
            ORDER BY p.created_at DESC
            LIMIT 6
        ');

        $categories = DB::select('
            SELECT * FROM categories
            WHERE is_active = 1
        ');

        return view('customer.home', compact('featured', 'categories'));
    }

    // ═══════════════════════════════════
    //  MENU PAGE
    // ═══════════════════════════════════
    public function index(Request $request)
    {
        $categories = DB::select('
            SELECT * FROM categories
            WHERE is_active = 1
        ');

        $sql    = 'SELECT p.*, c.name as category_name
                   FROM products p
                   JOIN categories c ON p.category_id = c.id
                   WHERE p.is_available = 1';
        $params = [];

        if ($request->category) {
            $sql      .= ' AND c.slug = ?';
            $params[] = $request->category;
        }

        if ($request->search) {
            $sql      .= ' AND p.name LIKE ?';
            $params[] = '%' . $request->search . '%';
        }

        $sql .= ' ORDER BY p.created_at DESC';

        $allProducts = DB::select($sql, $params);

        // Manual pagination
        $page     = $request->get('page', 1);
        $perPage  = 12;
        $total    = count($allProducts);
        $products = array_slice($allProducts, ($page - 1) * $perPage, $perPage);
        $lastPage = ceil($total / $perPage);

        return view('customer.menu', compact(
            'products', 'categories', 'page', 'lastPage', 'total'
        ));
    }

    // ═══════════════════════════════════
    //  SINGLE PRODUCT PAGE
    // ═══════════════════════════════════
    public function show($slug)
    {
        $product = DB::selectOne('
            SELECT p.*, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.is_available = 1
        ', [$slug]);

        if (!$product) abort(404);

        $related = DB::select('
            SELECT * FROM products
            WHERE category_id = ?
            AND id != ?
            AND is_available = 1
            LIMIT 4
        ', [$product->category_id, $product->id]);

        return view('customer.product', compact('product', 'related'));
    }
}