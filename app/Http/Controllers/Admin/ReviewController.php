<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display the list of reviews
     */
    public function index()
    {
        $reviews = Review::latest()->get();

        return response()->json([
            'message' => 'Lista de reseñas',
            'data' => $reviews
        ]);
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => $validated['user_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'approved' => false, // Puedes cambiar esto si quieres que se aprueben por defecto
        ]);

        return response()->json([
            'message' => 'Reseña creada correctamente',
            'data' => $review
        ], 201);
    }

    /**
     * Approve or disapprove a review
     */
    public function toggleApproveStatus(Review $review, $status)
    {
        $review->update([
            'approved' => filter_var($status, FILTER_VALIDATE_BOOLEAN)
        ]);

        return response()->json([
            'message' => 'Estado de aprobación actualizado',
            'data' => $review
        ]);
    }

    /**
     * Delete a review
     */
    public function delete(Review $review)
    {
        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada correctamente'
        ]);
    }
}
