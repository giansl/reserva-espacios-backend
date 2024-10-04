<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="SpaceRequest",
 *     type="object",
 *     title="Space Request",
 *     description="Esquema para la solicitud de creación de un espacio",
 *     required={"name", "capacity"},
 *     @OA\Property(property="name", type="string", example="Sala de conferencias"),
 *     @OA\Property(property="capacity", type="integer", example=50),
 *     @OA\Property(property="description", type="string", example="Sala equipada para conferencias y presentaciones"),
 *     @OA\Property(property="location", type="string", example="Edificio principal, piso 2"),
 *     @OA\Property(property="amenities", type="array", @OA\Items(type="string"), example={"proyector", "pizarra", "wifi"})
 * )
 */
class SpaceRequest
{
    // Esta clase puede estar vacía, ya que solo se usa para la documentación de OpenAPI
}
