<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Mostrar todos los productos disponibles para usuarios (solo activos)
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->where('status', true) // solo productos activos
            ->latest()
            ->get();

        return response()->json($products);
    }

    // Mostrar un solo producto con detalles
    public function show($id)
    {
        $product = Product::with(['category', 'brand'])
            ->where('status', true)
            ->findOrFail($id);

        return response()->json($product);
    }
}


