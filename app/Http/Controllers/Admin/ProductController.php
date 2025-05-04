<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Obtener todos los productos
     */
    public function index()
    {
        return ProductResource::collection(
            Product::with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
        ]);
    }

    /**
     * Obtener producto por slug
     */
    public function show(Product $product)
    {
        if (!$product) {
            abort(404);
        }

        return ProductResource::make(
            $product->load(['color', 'size', 'reviews', 'category', 'brand'])
        );
    }

    /**
     * Filtrar productos por categoría
     */
    public function filterProductsByCategory(Category $category)
    {
        return ProductResource::collection(
            $category->products()->with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $category->name
        ]);
    }

    /**
     * Filtrar productos por marca
     */
    public function filterProductsByBrand(Brand $brand)
    {
        return ProductResource::collection(
            $brand->products()->with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $brand->name
        ]);
    }

    /**
     * Filtrar productos por color
     */
    public function filterProductsByColor(Color $color)
    {
        return ProductResource::collection(
            $color->products()->with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $color->name
        ]);
    }

    /**
     * Filtrar productos por tamaño
     */
    public function filterProductsBySize(Size $size)
    {
        return ProductResource::collection(
            $size->products()->with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $size->name
        ]);
    }

    /**
     * Buscar productos por término
     */
    public function findProductsByTerm($searchTerm)
    {
        return ProductResource::collection(
            Product::where('name', 'LIKE', '%' . $searchTerm . '%')->with(['color', 'size', 'category', 'brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get()
        ]);
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

            $slug = $this->generateUniqueSlug($validated['name']);

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'price' => $validated['price'],
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'first_image' => $request->first_image,
                'second_image' => $request->second_image,
                'third_image' => $request->third_image,
                'status' => $request->status ?? 'activo',
                'qty' => $request->qty ?? 0,
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
     * Generar un slug único basado en el nombre
     */
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
