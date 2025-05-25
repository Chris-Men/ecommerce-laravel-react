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

public function applyCoupon(Request $request)
{
    $couponName = strtoupper($request->input('coupon'));

    $coupon = \App\Models\Coupon::where('name', $couponName)->first();

    if (!$coupon) {
        return response()->json(['message' => 'Cupón no válido.'], 404);
    }

    try {
        $validUntil = \Carbon\Carbon::parse($coupon->valid_until);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Fecha de expiración del cupón no válida.'], 500);
    }

    if ($validUntil->isPast()) {
        return response()->json(['message' => 'El cupón ha expirado.'], 400);
    }

    if (!$coupon->checkIfValid()) {
        return response()->json(['message' => 'El cupón no está disponible actualmente.'], 400);
    }

    return response()->json([
        'message' => 'Cupón aplicado correctamente.',
        'discount' => $coupon->discount,
        'coupon' => [
            'id' => $coupon->id,
            'name' => $coupon->name,
        ],
    ]);
}

public function summary(Request $request)
{
    $user = $request->user();
    $couponCode = $request->query('coupon');

    $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'El carrito está vacío.'], 400);
    }

    $subtotal = 0;
    $items = [];

    foreach ($cartItems as $item) {
        $price = $item->price ?? $item->product->price ?? 0;
        $lineTotal = $price * $item->qty;
        $subtotal += $lineTotal;

        $items[] = [
            'id' => $item->id,
            'product_name' => $item->product->name ?? 'Producto',
            'qty' => $item->qty,
            'unit_price' => round($price, 2),
            'line_total' => round($lineTotal, 2),
        ];
    }

    $discount = 0;
    $coupon = null;

    if ($couponCode) {
        $coupon = \App\Models\Coupon::where('name', strtoupper($couponCode))->first();

        if ($coupon && $coupon->checkIfValid()) {
            $discount = round($subtotal * ($coupon->discount / 100), 2);
        }
    }

    $total = round($subtotal - $discount, 2);

    return response()->json([
        'items' => $items,
        'subtotal' => round($subtotal, 2),
        'discount' => $discount,
        'total' => $total,
        'coupon_applied' => $coupon?->name
    ]);
}

}
