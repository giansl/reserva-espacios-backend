<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Reservation;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $space = Space::create($request->all());
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
    public function update(Request $request, string $id): JsonResponse
    {
        $space = Space::find($id);
        if (!$space) {
            return response()->json(['message' => 'Espacio no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $space->update($request->all());
        return response()->json($space);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $space = Space::find($id);
        if (!$space) {
            return response()->json(['message' => 'Espacio no encontrado'], 404);
        }

        // Verificar si hay reservas asociadas
        $reservationsCount = Reservation::where('space_id', $id)->count();
        if ($reservationsCount > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el espacio porque tiene reservas asociadas',
                'reservations_count' => $reservationsCount
            ], 409); // 409 Conflict
        }

        $space->delete();
        return response()->json(['message' => 'Espacio eliminado con éxito']);
    }
}
