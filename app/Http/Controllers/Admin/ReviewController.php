<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Obtener lista de todas las reseñas
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
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'title' => 'nullable|string|max:255'
        ]);

        $review = Review::create($validated);

        return response()->json([
            'message' => 'Reseña creada correctamente',
            'data' => $review
        ], 201);
    }

    // Mostrar una reseña específica
    public function show(Review $review)
    {
        return response()->json([
            'message' => 'Detalle de la reseña',
            'data' => $review
        ]);
    }

    // Actualizar una reseña existente
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'title' => 'nullable|string|max:255'
        ]);

        $review->update($validated);

        return response()->json([
            'message' => 'Reseña actualizada correctamente',
            'data' => $review
        ]);
    }

    // Eliminar una reseña
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada correctamente'
        ]);
    }
}
