<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    // Listar todos los cupones
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return response()->json([
            'coupons' => $coupons
        ]);
    }

    // Guardar un nuevo cupón
    public function store(AddCouponRequest $request)
    {
        $coupon = Coupon::create($request->validated());

        return response()->json([
            'message' => 'Cupón creado correctamente.',
            'data' => $coupon
        ], 201);
    }

    // Mostrar un cupón específico
    public function show(Coupon $coupon)
    {
        return response()->json([
            'coupon' => $coupon
        ]);
    }

    // Actualizar un cupón existente
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());

        return response()->json([
            'message' => 'Cupón actualizado correctamente.',
            'data' => $coupon
        ]);
    }

    // Eliminar un cupón
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json([
            'message' => 'Cupón eliminado correctamente.'
        ]);
    }
}
