<?php

namespace Tests\Feature;

use App\Models\Space;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Log;

class SpaceApiTest extends TestCase
{
    use RefreshDatabase;

    protected $excludedTables = ['personal_access_tokens'];

    protected function setUp(): void
    {
        parent::setUp();
    
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

    }

    public function test_index_returns_all_spaces()
    {
        Space::factory()->count(3)->create();

        $response = $this->getJson('/api/spaces');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_new_space()
    {
        $spaceData = [
            'name' => 'Sala de Conferencias',
            'capacity' => 50,
            'description' => 'Una sala amplia para conferencias',
            'type' => 'laboratorio',
        ];

        $response = $this->postJson('/api/spaces', $spaceData);

        $response->assertStatus(201)
            ->assertJsonFragment($spaceData);

        $this->assertDatabaseHas('spaces', $spaceData);
    }

    public function test_show_returns_specific_space()
    {
        $space = Space::factory()->create();

        $response = $this->getJson("/api/spaces/{$space->id}");

        $response->assertStatus(200)
            ->assertJson($space->toArray());
    }

    public function test_update_modifies_existing_space()
    {
        $space = Space::factory()->create();
        
        $updatedData = [
            'name' => 'laboratorio',
            'capacity' => 30,
            'type' => 'laboratorio',
        ];

        $response = $this->putJson("/api/spaces/{$space->id}", $updatedData);

        $updatedSpace = Space::find($space->id);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $space->id)
                     ->where('name', 'laboratorio')
                     ->where('capacity', 30)
                     ->where('type', 'laboratorio')
                     ->etc()
            );

        $this->assertDatabaseHas('spaces', $updatedData);
    }

    public function test_destroy_deletes_space_without_reservations()
    {
        $space = Space::factory()->create();

        $response = $this->deleteJson("/api/spaces/{$space->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Espacio eliminado con éxito']);

        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }

    public function test_destroy_fails_for_space_with_reservations()
    {
        $space = Space::factory()->create();
        Reservation::factory()->create(['space_id' => $space->id]);

        $response = $this->deleteJson("/api/spaces/{$space->id}");

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'No se puede eliminar el espacio porque tiene reservas asociadas',
                'reservations_count' => 1
            ]);

        $this->assertDatabaseHas('spaces', ['id' => $space->id]);
    }

    public function test_store_validates_input()
    {
        $invalidData = [
            'name' => '',
            'capacity' => 'no es un número',
            'type' => '',
            'description' => ''
        ];

        $response = $this->postJson('/api/spaces', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'capacity', 'type', 'description']);
    }

    public function test_update_validates_input()
    {
        $space = Space::factory()->create();

        $invalidData = [
            'capacity' => 'no es un número',
            'type' => 'tipo inválido',
        ];

        $response = $this->putJson("/api/spaces/{$space->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['capacity', 'type']);
    }

    public function test_show_returns_404_for_non_existent_space()
    {
        $response = $this->getJson("/api/spaces/999");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Espacio no encontrado']);
    }
}