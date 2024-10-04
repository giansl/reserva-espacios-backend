<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Space",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="capacity", type="integer"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(property="is_available", type="boolean"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Space extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'description', 'type'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
