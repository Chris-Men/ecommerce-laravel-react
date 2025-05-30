<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\AddProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'size', 'color'])->get();
        return ProductResource::collection($products);
    }

    public function store(AddProductRequest $request)
    {
        try {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'price' => $validated['price'],
                'description' => $validated['description'],
                'status' => $request->has('status') ? (bool)$request->status : true,
                'qty' => $validated['qty'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
                Log::info("Imagen guardada en: " . $data['image']);
            }

            $product = Product::create($data);

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'data' => new ProductResource($product)
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error en store(): ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(AddProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $request->file('image')->store('products', 'public');
                Log::info("Imagen actualizada en: " . $product->image);
            }

            $product->name = $validated['name'];
            $product->slug = $this->generateUniqueSlug($validated['name'], $product->id);
            $product->price = $validated['price'];
            $product->description = $validated['description'];
            $product->status = $request->has('status') ? (bool)$request->status : $product->status;
            $product->qty = $validated['qty'];
            $product->category_id = $validated['category_id'];
            $product->brand_id = $validated['brand_id'];
            $product->size_id = $validated['size_id'];
            $product->color_id = $validated['color_id'];

            $product->save();

            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'data' => new ProductResource($product)
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Error en update(): ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

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

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'size', 'color'])
            ->where('status', true)
            ->findOrFail($id);

        return response()->json(new ProductResource($product));
    }

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
}
