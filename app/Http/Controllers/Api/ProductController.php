<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Obtener todos los productos
     */
    public function index()
    {
        return ProductResource::collection(
            Product::with(['colors','sizes','category','brand'])->latest()->get()
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
        if(!$product) {
            abort(404);
        }

        return ProductResource::make(
            $product->load(['colors','sizes','reviews','category','brand'])
        );
    }

    /**
     * Filtrar productos por categoría
     */
    public function filterProductsByCategory(Category $category)
    {
        return ProductResource::collection(
            $category->products()->with(['colors','sizes','category','brand'])->latest()->get()
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
            $brand->products()->with(['colors','sizes','category','brand'])->latest()->get()
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
            $color->products()->with(['colors','sizes','category','brand'])->latest()->get()
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
            $size->products()->with(['colors','sizes','category','brand'])->latest()->get()
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
            Product::where('name','LIKE','%'.$searchTerm.'%')->with(['colors','sizes','category','brand'])->latest()->get()
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
        // Validar los datos entrantes
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            // Agrega las reglas de validación necesarias para otros campos
        ]);

        // Crear un nuevo producto
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            // Agrega otros campos según sea necesario
        ]);

        // Retornar el producto creado
        return response()->json(new ProductResource($product), 201);  // 201 es el código de estado para "Creado"
    }
}
