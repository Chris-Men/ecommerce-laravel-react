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
    public function storeUserOrders(Request $request)
    {
        try {
            $user = $request->user();
            $coupon = $request->input('coupon_id') ? Coupon::find($request->input('coupon_id')) : null;

            $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'El carrito está vacío'], 400);
            }

            $total = 0;
            foreach ($cartItems as $item) {
                $subtotal = $item->qty * $item->price;
                $total += $subtotal;
            }

            $discount = 0;
            if ($coupon && $coupon->checkIfValid()) {
                $discount = $total * $coupon->discount / 100;
            }

            $order = Order::create([
                'qty' => $cartItems->sum('qty'),
                'user_id' => $user->id,
                'coupon_id' => $coupon?->id,
                'total' => $total - $discount,
            ]);

            foreach ($cartItems as $item) {
                $order->products()->attach($item->product_id, ['quantity' => $item->qty]);
            }

            CartItem::where('user_id', $user->id)->delete();

            return response()->json([
                'message' => 'Orden creada',
                'order_id' => $order->id,
                'total' => $order->total
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear orden: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function payOrdersByStripe(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $cartItems = CartItem::with('product')->where('user_id', $request->user()->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'El carrito está vacío.'], 400);
            }

            $lineItems = [];

            foreach ($cartItems as $item) {
                $productName = $item->product->name ?? 'Producto';
                $unitPrice = $item->price ?? $item->product->price ?? 0;

                if ($unitPrice <= 0) {
                    throw new \Exception("Precio inválido para el producto {$productName}");
                }

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => $productName],
                        'unit_amount' => intval($unitPrice * 100),
                    ],
                    'quantity' => $item->qty,
                ];
            }

            $session = Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $request->input('success_url', 'https://example.com/success'),
                'cancel_url' => $request->input('cancel_url', 'https://example.com/cancel'),
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            Log::error('Error al procesar pago con Stripe: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en el pago con Stripe',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
