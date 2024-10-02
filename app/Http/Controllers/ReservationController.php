<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     */
    public function index(): JsonResponse
    {
        $reservations = Reservation::with(['user', 'space'])->get();
        return response()->json($reservations);
    }

    /**
     * Store a newly created reservation in storage.
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
     * Display the specified reservation.
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
     * Update the specified reservation in storage.
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
     * Remove the specified reservation from storage.
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
