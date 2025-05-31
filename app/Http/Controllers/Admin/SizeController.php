<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSizeRequest;
use App\Http\Requests\UpdateSizeRequest;
use App\Models\Size;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Listar todas las tallas
   public function index()
{
    $sizes = Size::latest()->get();

    return response()->json([
        'success' => true,
        'data' => $sizes,
        'sizes' => $sizes  // Para mantener compatibilidad con la vista original
    ]);
}

    // Guardar una nueva talla
    public function store(AddSizeRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $size = Size::create($data);

        return response()->json([
            'message' => 'Talla creada correctamente.',
            'data' => $size
        ], 201);
    }

    // Mostrar una talla específica
    public function show(Size $size)
    {
        return response()->json([
            'size' => $size
        ]);
    }

    // Actualizar una talla
    public function update(UpdateSizeRequest $request, Size $size)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $size->update($data);

        return response()->json([
            'message' => 'Talla actualizada correctamente.',
            'data' => $size
        ]);
    }

    // Eliminar una talla
    public function destroy(Size $size)
    {
        $size->delete();

        return response()->json([
            'message' => 'Talla eliminada correctamente.'
        ]);
    }
}
