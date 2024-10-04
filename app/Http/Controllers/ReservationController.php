<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Reserva de Espacios",
 *     version="1.0.0",
 *     description="API para gestionar reservas de espacios"
 * )
 * @OA\Tag(
 *     name="Reservations",
 *     description="Operaciones relacionadas con reservaciones"
 * )
 */
class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Obtener todas las reservaciones",
     *     tags={"Reservations"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservaciones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Reservation")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $reservations = Reservation::with(['user', 'space'])->get();
        return response()->json($reservations);
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Crear una nueva reservación",
     *     tags={"Reservations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservación creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva creada con éxito"),
     *             @OA\Property(property="reservation", ref="#/components/schemas/Reservation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la reservación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al crear la reserva"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        try {
            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'space_id' => $request->space_id,
                'event_name' => $request->event_name,
                'start' => $request->start,
                'end' => $request->end,
            ]);

            return response()->json([
                'message' => 'Reserva creada con éxito',
                'reservation' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la reserva',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Obtener una reservación específica",
     *     tags={"Reservations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reservación",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la reservación",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservación no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reservation not found")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $reservation = Reservation::with(['user', 'space'])->find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json($reservation);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/{id}",
     *     summary="Actualizar una reservación existente",
     *     tags={"Reservations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reservación",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservación actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva actualizada con éxito"),
     *             @OA\Property(property="reservation", ref="#/components/schemas/Reservation")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $reservation->update($request->all());
        return response()->json([
            'message' => 'Reserva actualizada con éxito',
            'reservation' => $reservation
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/reservations/{id}",
     *     summary="Eliminar una reservación",
     *     tags={"Reservations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reservación",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservación eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reservation deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservación no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reservation not found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
