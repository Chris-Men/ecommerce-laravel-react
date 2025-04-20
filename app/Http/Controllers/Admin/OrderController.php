<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Mostrar todos los pedidos
     */
    public function index()
    {
        $orders = Order::with(['products', 'user', 'coupon'])->latest()->get();
        return response()->json($orders);
    }

    /**
     * Guardar un nuevo pedido
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'coupon_id' => 'nullable|exists:coupons,id',
            'total' => 'required|numeric',
        ]);

        // Calculamos la cantidad total de productos
        $totalQty = collect($request->products)->sum('quantity');

        $order = Order::create([
            'user_id' => $request->user_id,
            'coupon_id' => $request->coupon_id,
            'total' => $request->total,
            'qty' => $totalQty,
        ]);

        foreach ($request->products as $product) {
            $order->products()->attach($product['product_id'], [
                'quantity' => $product['quantity']
            ]);
        }

        return response()->json([
            'message' => 'Pedido creado exitosamente',
            'order' => $order->load('products')
        ], 201);
    }



    /**
     * Marcar un pedido como entregado
     */
    public function updateDeliveredAtDate(Order $order)
    {
        $order->update([
            'delivered_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Pedido actualizado como entregado',
            'order' => $order
        ]);
    }

    /**
     * Eliminar un pedido
     */
    public function delete(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Pedido eliminado correctamente'
        ]);
    }
}
