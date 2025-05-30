<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Models\Color;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->get();

        return response()->json([
            'colors' => $colors
        ]);
    }

    public function store(AddColorRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $color = Color::create($data);

        return response()->json([
            'message' => 'Color creado correctamente.',
            'data' => $color
        ], 201);
    }

    public function show(Color $color)
    {
        return response()->json([
            'color' => $color
        ]);
    }

    public function update(UpdateColorRequest $request, Color $color)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $color->update($data);

        return response()->json([
            'message' => 'Color actualizado correctamente.',
            'data' => $color
        ]);
    }

    public function destroy(Color $color)
    {
        $color->delete();

        return response()->json([
            'message' => 'Color eliminado correctamente.'
        ]);
    }
}
