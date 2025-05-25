<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Obtener todas las categorías
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Mostrar una categoría específica por slug + sus productos
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'error' => 'Categoría no encontrada.'
            ], 404);
        }

        $products = $category->products()->where('status', true)->get();

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }



    public function productsByCategory($slug)
{
    $category = Category::where('slug', $slug)->first();

    if (!$category) {
        return response()->json(['error' => 'Categoría no encontrada.'], 404);
    }

$products = $category->products()->where('status', true)->latest()->get();

    return response()->json([
        'category' => $category->name,
        'products' => $products,
    ]);
}

}
