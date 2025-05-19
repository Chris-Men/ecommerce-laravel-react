<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Listar todos los productos
     */
    public function index()
    {
        $products = Product::with(['category', 'brand', 'size', 'color'])->get();
        return ProductResource::collection($products);
    }

    /**
     * Crear un nuevo producto
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'size_id' => 'required|exists:sizes,id',
                'color_id' => 'required|exists:colors,id',
            ]);

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'price' => $validated['price'],
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'first_image' => $request->first_image,
                'second_image' => $request->second_image,
                'third_image' => $request->third_image,
                'status' => $request->has('status') ? (bool)$request->status : true,

                'qty' => $request->qty,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ]);

            return response()->json(new ProductResource($product), 201);
        } catch (\Throwable $e) {
            Log::error('Error en store(): ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'size_id' => 'required|exists:sizes,id',
                'color_id' => 'required|exists:colors,id',
            ]);

            $product->update([
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name'], $product->id),
                'price' => $validated['price'],
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'first_image' => $request->first_image,
                'second_image' => $request->second_image,
                'third_image' => $request->third_image,
                'status' => $request->status ?? $product->status,
                'qty' => $request->qty ?? $product->qty,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ]);

            return response()->json(new ProductResource($product), 200);
        } catch (\Throwable $e) {
            Log::error('Error en update(): ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un producto
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente.'
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error en destroy(): ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar slug único
     */
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
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
