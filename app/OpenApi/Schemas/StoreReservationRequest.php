<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="StoreReservationRequest",
 *     type="object",
 *     title="Store Reservation Request",
 *     required={"space_id", "event_name", "start", "end"},
 *     @OA\Property(property="space_id", type="integer"),
 *     @OA\Property(property="event_name", type="string"),
 *     @OA\Property(property="start", type="string", format="date-time"),
 *     @OA\Property(property="end", type="string", format="date-time")
 * )
 */
class StoreReservationRequest
{
}
