<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Obtener todas las categorías activas (para usuarios)
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Mostrar una categoría específica por ID
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'error' => 'Categoría no encontrada.'
            ], 404);
        }

        return response()->json([
            'category' => $category
        ]);
    }
}
