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
    public function store(AddProductRequest $request)
    {
        try {
            $validated = $request->validated();

            $data = [
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'price' => $validated['price'],
                'description' => $validated['description'], // Corregido: usar 'description' consistentemente
                'status' => $request->has('status') ? (bool)$request->status : true,
                'qty' => $validated['qty'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ];

            // Guardar imágenes
            foreach (['thumbnail', 'first_image', 'second_image', 'third_image'] as $imageField) {
                if ($request->hasFile($imageField)) {
                    $data[$imageField] = $request->file($imageField)->store('products', 'public');
                    Log::info("$imageField guardado en: " . $data[$imageField]);
                }
            }

            $product = Product::create($data);

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'data' => new ProductResource($product)
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error en store(): ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function update(AddProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            // Reemplazar imágenes si fueron enviadas
            foreach (['thumbnail', 'first_image', 'second_image', 'third_image'] as $imageField) {
                if ($request->hasFile($imageField)) {
                    // Eliminar imagen antigua si existe
                    if ($product->$imageField) {
                        Storage::disk('public')->delete($product->$imageField);
                    }
                    // Guardar nueva imagen
                    $product->$imageField = $request->file($imageField)->store('products', 'public');
                    Log::info("$imageField actualizado en: " . $product->$imageField);
                }
            }

            // Actualizar campos
            $product->name = $validated['name'];
            $product->slug = $this->generateUniqueSlug($validated['name'], $product->id);
            $product->price = $validated['price'];
            $product->description = $validated['description']; // Corregido: usar 'description'
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
            Log::error('Stack trace: ' . $e->getTraceAsString());

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
            // Eliminar imágenes asociadas
            foreach (['thumbnail', 'first_image', 'second_image', 'third_image'] as $imageField) {
                if ($product->$imageField) {
                    Storage::disk('public')->delete($product->$imageField);
                }
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

    /**
     * Mostrar un solo producto con detalles
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'size', 'color'])
            ->where('status', true)
            ->findOrFail($id);

        return response()->json(new ProductResource($product));
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
}
