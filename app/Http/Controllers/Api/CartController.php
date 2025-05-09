<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    // Ver todos los productos en el carrito del usuario
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
                    'total' => $item->qty * $item->price
                ];
            });

        return response()->json($items);
    }

    // Agregar o actualizar un producto en el carrito
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $item = CartItem::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $validated['product_id']],
            ['qty' => $validated['qty'], 'price' => $product->price]
        );

        return response()->json($item);
    }

    // Actualizar la cantidad de un producto específico en el carrito
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $cartItem->qty = $request->qty;
        $cartItem->save();

        return response()->json([
            'message' => 'Cantidad actualizada correctamente',
            'item' => $cartItem
        ]);
    }

    // Eliminar un producto del carrito
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }

    // Vaciar todo el carrito del usuario
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Carrito vaciado']);
    }
}
