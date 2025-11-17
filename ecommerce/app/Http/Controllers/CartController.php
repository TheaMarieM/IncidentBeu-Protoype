<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $existing = CartItem::where('product_id', $validated['product_id'])->first();
        if ($existing) {
            $existing->quantity += $validated['quantity'];
            $existing->save();
            return response()->json(['message' => 'Cart updated', 'item' => $existing], 200);
        }

        $item = CartItem::create($validated);
        return response()->json(['message' => 'Added to cart', 'item' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $data = $request->validate(['quantity' => 'required|integer|min:1']);
        $item->update($data);
        return response()->json(['message' => 'Cart updated', 'item' => $item]);
    }

    public function patch(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        if ($request->has('quantity')) {
            $item->quantity = $request->input('quantity');
            $item->save();
        }
        return response()->json(['message' => 'Cart item patched', 'item' => $item]);
    }

    public function destroy($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }
}
