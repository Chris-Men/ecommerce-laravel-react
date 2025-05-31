<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    // Obtener todas las marcas y devolverlas como respuesta JSON
    $brands = Brand::latest()->get();

    return response()->json([
        'success' => true,
        'data' => $brands,
        'brands' => $brands  // Para mantener compatibilidad con la vista original
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddBrandRequest $request)
    {
        // Obtener los datos validados del formulario
        $data = $request->validated();
        // Generar un slug a partir del nombre de la marca
        $data['slug'] = Str::slug($data['name']);

        // Crear la marca en la base de datos
        $brand = Brand::create($data);

        // Devolver una respuesta JSON con la marca creada
        return response()->json([
            'message' => 'Marca creada correctamente.',
            'data' => $brand
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        // Mostrar detalles de una marca específica
        return response()->json([
            'brand' => $brand
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        // Este método no se usa para la API, así que lo omitimos aquí.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        // Validar los datos de la solicitud
        if ($request->validated()) {
            // Obtener los datos validados
            $data = $request->validated();
            // Generar el slug actualizado
            $data['slug'] = Str::slug($data['name']);
            // Actualizar la marca
            $brand->update($data);

            // Devolver una respuesta JSON con la marca actualizada
            return response()->json([
                'message' => 'Marca actualizada correctamente.',
                'data' => $brand
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Eliminar la marca
        $brand->delete();

        // Devolver una respuesta JSON indicando que la marca fue eliminada
        return response()->json([
            'message' => 'Marca eliminada correctamente.'
        ]);
    }
}
