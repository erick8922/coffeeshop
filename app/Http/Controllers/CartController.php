<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    // ═══════════════════════════════════
    //  GET CART OF USER
    // ═══════════════════════════════════
    private function getCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => auth()->id()]
        );
    }

    // ═══════════════════════════════════
    //  SHOW CART PAGE
    // ═══════════════════════════════════
    public function index()
    {
        $cart  = $this->getCart();
        $items = $cart->items()->with('product')->get();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return view('customer.cart', compact('cart', 'items', 'total'));
    }

    // ═══════════════════════════════════
    //  ADD TO CART
    // ═══════════════════════════════════
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size'       => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_available) {
            return back()->with('error', 'Sorry, this product is not available.');
        }

        $cart = $this->getCart();

        $cartItem = $cart->items()
                         ->where('product_id', $request->product_id)
                         ->where('size', $request->size)
                         ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
                'size'       => $request->size,
                'extras'     => $request->extras ?? null,
            ]);
        }

        return back()->with('success', $product->name . ' added to cart successfully!');
    }

    // ═══════════════════════════════════
    //  UPDATE CART ITEM QUANTITY
    // ═══════════════════════════════════
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $id)
                            ->whereHas('cart', fn($q) => $q->where('user_id', auth()->id()))
                            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully!');
    }

    // ═══════════════════════════════════
    //  REMOVE ITEM FROM CART
    // ═══════════════════════════════════
    public function remove($id)
    {
        $cartItem = CartItem::where('id', $id)
                            ->whereHas('cart', fn($q) => $q->where('user_id', auth()->id()))
                            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart successfully!');
    }

    // ═══════════════════════════════════
    //  CLEAR ALL ITEMS FROM CART
    // ═══════════════════════════════════
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return back()->with('success', 'Cart cleared successfully!');
    }
}