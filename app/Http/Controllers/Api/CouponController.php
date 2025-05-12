<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Apply coupon
     */
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::whereName($request->name)->first();
        if($coupon && $coupon->checkIfValid()) {
            return response()->json([
                'message' => 'Cupón aplicado exitosamente',
                'coupon' => $coupon
            ]);
        } else {
            return response()->json([
                'error' => 'Cupón no válido o caducado'
            ]);
        }
    }


    public function index()
{
    $coupons = Coupon::where('valid_until', '>=', now())->get();

    return response()->json([
        'coupons' => $coupons
    ]);
}

    /**
     * a new coupon
     */
    public function store(Request $request)
    {
        // Validación de los campos recibidos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:coupons,name',
            'discount' => 'required|numeric|min:0',
            'valid_until' => 'required|date|after:today',  // Asegura que la fecha no esté en el pasado
        ]);

        // Creación del nuevo cupón
        $coupon = Coupon::create([
            'name' => $validated['name'],
            'discount' => $validated['discount'],
            'valid_until' => $validated['valid_until'],
        ]);

        // Respuesta al cliente
        return response()->json([
            'message' => 'Cupón creado correctamente',
            'coupon' => $coupon
        ], 201);
    }
}
