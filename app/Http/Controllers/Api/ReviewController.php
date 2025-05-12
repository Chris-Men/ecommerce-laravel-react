<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Mostrar las reseñas del usuario autenticado
     */
    public function index(Request $request)
    {
        // Solo muestra las reseñas del usuario autenticado
        $reviews = Review::where('user_id', $request->user()->id)->latest()->get();

        return response()->json([
            'message' => 'Tus reseñas',
            'data' => $reviews
        ]);
    }

    /**
     * Crear una nueva reseña (producto o general)
     */
    public function store(Request $request)
    {
        // Validar la entrada
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',  // Puede ser null si es reseña general
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'title' => 'nullable|string|max:255'
        ]);

        // Verificar si es reseña de un producto
        if ($request->filled('product_id')) {
            // Verifica si ya existe una reseña del producto por este usuario
            $existingReview = Review::where('product_id', $request->product_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'error' => 'Ya has reseñado este producto.'
                ], 422);
            }
        }

        // Crear la reseña
        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'approved' => false // Puede ser true si quieres que se aprueben por defecto
        ]);

        return response()->json([
            'message' => 'Reseña creada correctamente.',
            'data' => $review
        ], 201);
    }
}
