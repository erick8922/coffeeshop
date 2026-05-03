<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ═══════════════════════════════════
    //  GET OR CREATE CART
    // ═══════════════════════════════════
    private function getCart()
    {
        $userId = Auth::id();
        $cart   = DB::selectOne('
            SELECT * FROM carts WHERE user_id = ?
        ', [$userId]);

        if (!$cart) {
            DB::insert('
                INSERT INTO carts (user_id, created_at, updated_at)
                VALUES (?, NOW(), NOW())
            ', [$userId]);

            $cart = DB::selectOne('
                SELECT * FROM carts WHERE user_id = ?
            ', [$userId]);
        }

        return $cart;
    }

    // ═══════════════════════════════════
    //  SHOW CART PAGE
    // ═══════════════════════════════════
    public function index()
    {
        $cart = $this->getCart();

        $items = DB::select('
            SELECT ci.*, p.name as product_name, p.price as product_price,
                   p.image as product_image
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ', [$cart->id]);

        $total = array_sum(array_map(
            fn($item) => $item->product_price * $item->quantity,
            $items
        ));

        return view('customer.cart', compact('cart', 'items', 'total'));
    }

    // ═══════════════════════════════════
    //  ADD TO CART (AJAX)
    // ═══════════════════════════════════
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size'       => 'nullable|string',
        ]);

        $product = DB::selectOne('
            SELECT * FROM products WHERE id = ?
        ', [$request->product_id]);

        if (!$product->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this product is not available.'
            ], 400);
        }

        $cart = $this->getCart();

        $cartItem = DB::selectOne('
            SELECT * FROM cart_items
            WHERE cart_id = ? AND product_id = ? AND size = ?
        ', [$cart->id, $request->product_id, $request->size]);

        if ($cartItem) {
            DB::update('
                UPDATE cart_items
                SET quantity = quantity + ?, updated_at = NOW()
                WHERE id = ?
            ', [$request->quantity, $cartItem->id]);
        } else {
            DB::insert('
                INSERT INTO cart_items
                (cart_id, product_id, quantity, size, extras, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ', [
                $cart->id,
                $request->product_id,
                $request->quantity,
                $request->size,
                $request->extras ?? null,
            ]);
        }

        // Count cart items para sa badge
        $cartCount = DB::selectOne('
            SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?
        ', [$cart->id]);

        return response()->json([
            'success'    => true,
            'message'    => $product->name . ' added to cart successfully!',
            'cart_count' => $cartCount->count,
        ]);
    }

    // ═══════════════════════════════════
    //  UPDATE CART ITEM (AJAX)
    // ═══════════════════════════════════
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $cartItem = DB::selectOne('
            SELECT ci.* FROM cart_items ci
            JOIN carts c ON ci.cart_id = c.id
            WHERE ci.id = ? AND c.user_id = ?
        ', [$id, $userId]);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        DB::update('
            UPDATE cart_items
            SET quantity = ?, updated_at = NOW()
            WHERE id = ?
        ', [$request->quantity, $id]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
        ]);
    }

    // ═══════════════════════════════════
    //  REMOVE ITEM (AJAX)
    // ═══════════════════════════════════
    public function remove($id)
    {
        $userId = Auth::id();

        $cartItem = DB::selectOne('
            SELECT ci.* FROM cart_items ci
            JOIN carts c ON ci.cart_id = c.id
            WHERE ci.id = ? AND c.user_id = ?
        ', [$id, $userId]);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        DB::delete('
            DELETE FROM cart_items WHERE id = ?
        ', [$id]);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully!',
        ]);
    }

    // ═══════════════════════════════════
    //  CLEAR CART (AJAX)
    // ═══════════════════════════════════
    public function clear()
    {
        $cart = $this->getCart();

        DB::delete('
            DELETE FROM cart_items WHERE cart_id = ?
        ', [$cart->id]);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!',
        ]);
    }
}