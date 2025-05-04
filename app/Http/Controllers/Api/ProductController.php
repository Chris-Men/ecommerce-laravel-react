<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Listar todos los productos con categoría y marca
    public function index()
    {
        return response()->json(
            Product::with(['category', 'brand'])->latest()->get(),
            200
        );
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'thumbnail' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|boolean',
            'first_image' => 'nullable|string',
            'second_image' => 'nullable|string',
            'third_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($validator->validated());

        return response()->json($product, 201);
    }

    // Mostrar producto específico con relaciones
    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'brand']), 200);
    }

    // Actualizar producto
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'thumbnail' => 'nullable|string',
            'first_image' => 'nullable|string',
            'second_image' => 'nullable|string',
            'third_image' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|boolean',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($validator->validated());

        return response()->json($product, 200);
    }

    // Eliminar producto
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}
