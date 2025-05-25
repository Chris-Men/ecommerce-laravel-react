<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Mostrar todos los cupones válidos creados por el administrador.
     */
    public function index()
    {
        $coupons = Coupon::where('valid_until', '>=', now())->get();

        return response()->json([
            'message' => 'Lista de cupones válidos',
            'coupons' => $coupons
        ]);
    }

    /**
     * Aplicar un cupón por nombre.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $coupon = Coupon::where('name', strtoupper($request->name))->first();

        if ($coupon && $coupon->checkIfValid()) {
            return response()->json([
                'message' => 'Cupón aplicado exitosamente',
                'coupon' => $coupon
            ]);
        }

        return response()->json([
            'error' => 'Cupón no válido o caducado'
        ], 400);
    }
}
