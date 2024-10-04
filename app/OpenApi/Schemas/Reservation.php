<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="ReservationSchema",
 *     required={"id", "user", "space", "start_time", "end_time"}
 * )
 */
class Reservation
{
    /**
     * @OA\Property(property="id", type="integer")
     */
    public $id;

    /**
     * @OA\Property(
     *     property="user",
     *     ref="#/components/schemas/User"
     * )
     */
    public $user;

    /**
     * @OA\Property(property="space_id", type="integer")
     */
    public $space_id;

    /**
     * @OA\Property(property="event_name", type="string")
     */
    public $event_name;

    /**
     * @OA\Property(property="start", type="string", format="date-time")
     */
    public $start;

    /**
     * @OA\Property(property="end", type="string", format="date-time")
     */
    public $end;

    /**
     * @OA\Property(property="created_at", type="string", format="date-time")
     */
    public $created_at;

    /**
     * @OA\Property(property="updated_at", type="string", format="date-time")
     */
    public $updated_at;

    /**
     * @OA\Property(property="space", ref="#/components/schemas/Space")
     */
    public $space;
}
