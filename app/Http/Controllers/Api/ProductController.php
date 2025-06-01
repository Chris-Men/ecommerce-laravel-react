<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Mostrar todos los productos disponibles para usuarios (solo activos)
    public function index()
    {
        $products = Product::with(['category', 'brand', 'size', 'color'])
            ->where('status', true) // solo productos activos
            ->latest()
            ->get();

        return ProductResource::collection($products);
    }

    // Mostrar un solo producto con detalles
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'size', 'color'])
            ->where('status', true)
            ->findOrFail($id);

        return new ProductResource($product);
    }
}
