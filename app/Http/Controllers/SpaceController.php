<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $spaces = Space::all();
        return response()->json($spaces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|in:sala,auditorio,laboratorio', // Ajusta según tus tipos válidos
            'description' => 'required|string',
        ]);

        // Crear el espacio con los datos validados
        $space = Space::create($validatedData);

        return response()->json($space, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $space = Space::find($id);
        if (!$space) {
            return response()->json(['message' => 'Espacio no encontrado'], 404);
        }
        return response()->json($space);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Space $space)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|string|in:sala,auditorio,laboratorio',
        ]);

        $space->update($validatedData);

        return response()->json($space, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $space = Space::find($id);
        try {
            
            if (!$space) {
                return response()->json(['message' => 'Espacio no encontrado'], 404);
            }
            $space->delete();
            return response()->json(['message' => 'Espacio eliminado con éxito']);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $reservationsCount = $space->reservations()->count();
                return response()->json([
                    'message' => 'No se puede eliminar el espacio porque tiene reservas asociadas',
                    'reservations_count' => $reservationsCount
                ], 409);
            }
            throw $e;
        }
        
    }
}
