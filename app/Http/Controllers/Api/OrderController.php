<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
  public function payOrdersByStripe(Request $request)
{
    try {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $user = $request->user();
        $couponCode = strtoupper($request->input('coupon_code'));
        $coupon = $couponCode ? Coupon::where('name', $couponCode)->first() : null;

        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 400);
        }

        $subtotal = 0;
        $lineItems = [];

        foreach ($cartItems as $item) {
            $unitPrice = $item->price ?? $item->product->price ?? 0;
            $productName = $item->product->name ?? 'Producto';

            if ($unitPrice <= 0) {
                throw new \Exception("Precio inválido para el producto {$productName}");
            }

            $subtotal += $unitPrice * $item->qty;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $productName],
                    'unit_amount' => intval(round($unitPrice * 100)), // Stripe usa centavos
                ],
                'quantity' => $item->qty,
            ];
        }

        // Calcular descuento si el cupón es válido
        $discount = 0;
        if ($coupon && $coupon->checkIfValid()) {
            $discount = round($subtotal * ($coupon->discount / 100), 2);
        }

        $total = round($subtotal - $discount, 2);

        // Guardar la orden en la base de datos
        $order = Order::create([
            'qty' => $cartItems->sum('qty'),
            'user_id' => $user->id,
            'coupon_id' => $coupon?->id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);

        // Asociar productos a la orden
        foreach ($cartItems as $item) {
            $order->products()->attach($item->product_id, [
                'quantity' => $item->qty,
                'price' => $item->price,
            ]);
        }

        // Vaciar carrito
        CartItem::where('user_id', $user->id)->delete();

        // Crear sesión Stripe (nota: aquí el descuento no se refleja directamente)
        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $request->input('success_url', 'https://example.com/success'),
            'cancel_url' => $request->input('cancel_url', 'https://example.com/cancel'),
        ]);

        return response()->json([
            'message' => 'Sesión de pago creada',
            'url' => $session->url
        ]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error al procesar pago con Stripe: ' . $e->getMessage());
        return response()->json([
            'message' => 'Error en el pago con Stripe',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
