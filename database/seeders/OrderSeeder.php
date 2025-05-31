<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = User::inRandomOrder()->take(4)->get(); // usa 4 usuarios para repetirlos
        $cupones = Coupon::inRandomOrder()->get();
        $productos = Product::all();

        for ($i = 0; $i < 12; $i++) {
            $usuario = $usuarios->random();

            // Selección de productos aleatorios
            $productosSeleccionados = $productos->random(rand(1, 4));

            $subtotal = 0;
            $items = [];

            foreach ($productosSeleccionados as $producto) {
                $cantidad = rand(1, 4);
                $subtotal += $producto->price * $cantidad;
                $items[$producto->id] = [
                    'quantity' => $cantidad,
                    'price' => $producto->price
                ];
            }

            // Cupones aleatorios (algunos pedidos no tienen cupón)
            $cupon = rand(0, 1) ? $cupones->random() : null;
            $descuento = $cupon ? ($subtotal * ($cupon->discount / 100)) : 0;
            $total = $subtotal - $descuento;

            $orden = Order::create([
                'qty' => collect($items)->sum('quantity'),
                'subtotal' => $subtotal,
                'discount' => $descuento,
                'total' => $total,
                'delivered_at' => now()->subDays(rand(0, 30)),
                'user_id' => $usuario->id,
                'coupon_id' => $cupon?->id,
            ]);

            $orden->products()->attach($items);
        }
    }
}
