<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User"
 * )
 */
class User
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     format="int64"
     * )
     */
    public $id;

    /**
     * @OA\Property(
     *     property="name",
     *     type="string"
     * )
     */
    public $name;

    // ... otros campos del usuario ...
}
