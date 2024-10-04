<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Espacios",
 *     description="Operaciones relacionadas con espacios"
 * )
 */
class SpaceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/spaces",
     *     summary="Obtener lista de espacios",
     *     tags={"Espacios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de espacios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Space")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $spaces = Space::all();
        return response()->json($spaces);
    }

    /**
     * @OA\Post(
     *     path="/api/spaces",
     *     summary="Crear un nuevo espacio",
     *     tags={"Espacios"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/SpaceRequest")
     *     ),
     *     @OA\Response(response=201, description="Espacio creado exitosamente"),
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|in:sala,auditorio,laboratorio',
            'description' => 'required|string',
        ]);

        $space = Space::create($validatedData);

        return response()->json($space, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/spaces/{id}",
     *     summary="Obtener un espacio específico",
     *     tags={"Espacios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del espacio",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del espacio",
     *         @OA\JsonContent(ref="#/components/schemas/Space")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Espacio no encontrado"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/spaces/{id}",
     *     summary="Actualizar un espacio existente",
     *     tags={"Espacios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del espacio",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SpaceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Espacio actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Space")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/spaces/{id}",
     *     summary="Eliminar un espacio",
     *     tags={"Espacios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del espacio",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Espacio eliminado con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Espacio no encontrado"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="No se puede eliminar el espacio porque tiene reservas asociadas"
     *     )
     * )
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