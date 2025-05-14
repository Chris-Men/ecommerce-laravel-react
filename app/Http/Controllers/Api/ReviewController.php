<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Mostrar reseñas del usuario autenticado
    public function index()
    {
        $reviews = Review::latest()->get();

        return response()->json([
            'message' => 'Lista de reseñas',
            'data' => $reviews
        ]);
    }

    // Crear una nueva reseña
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'title' => 'nullable|string|max:255'
        ]);

        // Evitar reseña duplicada
        $exists = Review::where('product_id', $validated['product_id'])
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => 'Ya has dado tu reseña de  este producto.'
            ], 422);
        }

        // Crear la reseña
        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'title' => $validated['title'] ?? null
        ]);

        return response()->json([
            'message' => 'Reseña creada correctamente.',
            'data' => $review
        ], 201);
    }
}
