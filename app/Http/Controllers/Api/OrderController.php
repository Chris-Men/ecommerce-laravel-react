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
                        'unit_amount' => intval(round($unitPrice * 100)),
                    ],
                    'quantity' => $item->qty,
                ];
            }

            // Calcular descuento si el cupón es válido
            $discount = 0;
            $discountPercentage = 0;
            if ($coupon && $coupon->checkIfValid()) {
                $discount = round($subtotal * ($coupon->discount / 100), 2);
                $discountPercentage = $coupon->discount;
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

            // SOLUCIÓN 1: Crear cupón de descuento en Stripe (RECOMENDADO)
            $sessionData = [
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $request->input('success_url', 'https://example.com/success'),
                'cancel_url' => $request->input('cancel_url', 'https://example.com/cancel'),
            ];

            // Si hay descuento, aplicarlo en Stripe
            if ($discount > 0 && $discountPercentage > 0) {
                // Crear cupón en Stripe
                $stripeCoupon = \Stripe\Coupon::create([
                    'percent_off' => $discountPercentage,
                    'duration' => 'once',
                    'name' => $couponCode,
                ]);

                $sessionData['discounts'] = [[
                    'coupon' => $stripeCoupon->id,
                ]];
            }

            $session = Session::create($sessionData);

            return response()->json([
                'message' => 'Sesión de pago creada',
                'url' => $session->url,
                'order_id' => $order->id,
                'total_applied' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('Error al procesar pago con Stripe: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en el pago con Stripe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // SOLUCIÓN 2: Alternativa - Aplicar descuento directamente a los precios
    public function payOrdersByStripeAlternative(Request $request)
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
            $discount = 0;
            $discountMultiplier = 1;

            // Calcular descuento
            if ($coupon && $coupon->checkIfValid()) {
                $discountMultiplier = 1 - ($coupon->discount / 100);
            }

            $lineItems = [];

            foreach ($cartItems as $item) {
                $unitPrice = $item->price ?? $item->product->price ?? 0;
                $productName = $item->product->name ?? 'Producto';

                if ($unitPrice <= 0) {
                    throw new \Exception("Precio inválido para el producto {$productName}");
                }

                $originalSubtotal = $unitPrice * $item->qty;
                $subtotal += $originalSubtotal;

                // Aplicar descuento directamente al precio
                $discountedPrice = round($unitPrice * $discountMultiplier, 2);

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $coupon ? "{$productName} (Descuento {$coupon->discount}% aplicado)" : $productName
                        ],
                        'unit_amount' => intval(round($discountedPrice * 100)),
                    ],
                    'quantity' => $item->qty,
                ];
            }

            if ($coupon && $coupon->checkIfValid()) {
                $discount = round($subtotal * ($coupon->discount / 100), 2);
            }

            $total = round($subtotal - $discount, 2);

            // Guardar la orden
            $order = Order::create([
                'qty' => $cartItems->sum('qty'),
                'user_id' => $user->id,
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ]);

            // Asociar productos
            foreach ($cartItems as $item) {
                $order->products()->attach($item->product_id, [
                    'quantity' => $item->qty,
                    'price' => $item->price,
                ]);
            }

            // Vaciar carrito
            CartItem::where('user_id', $user->id)->delete();

            // Crear sesión con precios ya descontados
            $session = Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $request->input('success_url', 'https://example.com/success'),
                'cancel_url' => $request->input('cancel_url', 'https://example.com/cancel'),
            ]);

            return response()->json([
                'message' => 'Sesión de pago creada',
                'url' => $session->url,
                'order_id' => $order->id,
                'total_applied' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('Error al procesar pago con Stripe: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en el pago con Stripe',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
