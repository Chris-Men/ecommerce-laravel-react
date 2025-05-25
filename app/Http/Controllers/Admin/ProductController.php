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
            // Validación completa y coherente con mimes
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'size_id' => 'required|exists:sizes,id',
                'color_id' => 'required|exists:colors,id',

                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'first_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'second_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'third_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $data = [
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'price' => $validated['price'],
                'description' => $request->description ?? null,
                'status' => $request->has('status') ? (bool)$request->status : true,
                'qty' => $request->qty ?? 0,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ];

            // Guardar imágenes y asignar rutas
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
                Log::info('Thumbnail guardado en: ' . $data['thumbnail']);
            }

            if ($request->hasFile('first_image')) {
                $data['first_image'] = $request->file('first_image')->store('products', 'public');
                Log::info('First image guardada en: ' . $data['first_image']);
            }

            if ($request->hasFile('second_image')) {
                $data['second_image'] = $request->file('second_image')->store('products', 'public');
                Log::info('Second image guardada en: ' . $data['second_image']);
            }

            if ($request->hasFile('third_image')) {
                $data['third_image'] = $request->file('third_image')->store('products', 'public');
                Log::info('Third image guardada en: ' . $data['third_image']);
            }

            $product = Product::create($data);

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
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'first_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'second_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'third_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('thumbnail')) {
                $product->thumbnail = $request->file('thumbnail')->store('products', 'public');
                Log::info('Thumbnail actualizado en: ' . $product->thumbnail);
            }

            if ($request->hasFile('first_image')) {
                $product->first_image = $request->file('first_image')->store('products', 'public');
                Log::info('First image actualizada en: ' . $product->first_image);
            }

            if ($request->hasFile('second_image')) {
                $product->second_image = $request->file('second_image')->store('products', 'public');
                Log::info('Second image actualizada en: ' . $product->second_image);
            }

            if ($request->hasFile('third_image')) {
                $product->third_image = $request->file('third_image')->store('products', 'public');
                Log::info('Third image actualizada en: ' . $product->third_image);
            }

            // Actualizar campos
            $product->name = $validated['name'];
            $product->slug = $this->generateUniqueSlug($validated['name'], $product->id);
            $product->price = $validated['price'];
            $product->description = $request->description ?? $product->description;
            $product->status = $request->has('status') ? (bool)$request->status : $product->status;
            $product->qty = $request->qty ?? $product->qty;
            $product->category_id = $validated['category_id'];
            $product->brand_id = $validated['brand_id'];
            $product->size_id = $validated['size_id'];
            $product->color_id = $validated['color_id'];

            $product->save();

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

    /**
     * Mostrar un solo producto con detalles
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'size', 'color'])
            ->where('status', true)
            ->findOrFail($id);

        return response()->json($product);
    }
}
