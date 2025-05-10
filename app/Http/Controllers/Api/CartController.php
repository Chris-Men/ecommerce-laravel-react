<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    // Ver productos del carrito
    public function index(Request $request)
    {
        $items = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->qty * $item->price,
                ];
            });

        return response()->json($items);
    }

    // Agregar o actualizar producto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $item = CartItem::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $validated['product_id']],
            ['qty' => $validated['qty'], 'price' => $product->price]
        );

        return response()->json($item);
    }

    // Actualizar cantidad
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Producto no encontrado en el carrito.'], 404);
        }

        $cartItem->qty = $validated['qty'];
        $cartItem->save();

        return response()->json([
            'message' => 'Cantidad actualizada correctamente.',
            'item' => $cartItem,
        ]);
    }

    // Eliminar producto del carrito
    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Producto no encontrado en el carrito.'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Producto eliminado del carrito.']);
    }

    // Vaciar el carrito
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Carrito vaciado correctamente.']);
    }
}
