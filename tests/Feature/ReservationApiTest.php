<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Role;
use App\Models\Space;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $space;
    protected $reservations;

    protected function setUp(): void
    {
        parent::setUp();
        
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        
        $this->user = User::factory()->create(['role_id' => $clientRole->id]);
        
        $this->space = Space::factory()->create();
        
        $this->reservations = Reservation::factory()->count(3)->create();
        $this->reservations->each(function ($reservation) {
            $reservation->space()->associate($this->space);
        });
    }

    public function test_index_returns_list_of_reservations()
    {
        $this->actingAs($this->user);

        Reservation::factory()->count(3)->create();

        $response = $this->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonCount(6);
    }

    public function test_store_creates_new_reservation()
    {
       
        $this->actingAs($this->user);

        // Datos de la reserva
        $reservationData = [
            'space_id' => 8,
            'event_name' => 'Test Event',
            'start' => '2024-11-15T10:00:00.000000Z',
            'end' => '2024-11-15T12:00:00.000000Z',
        ];

        // Realizar la solicitud POST
        $response = $this->postJson('/api/reservations', $reservationData);

        // Verificar la respuesta
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'reservation' => [
                    'id', 'user_id', 'space_id', 'event_name', 'start', 'end', 'created_at', 'updated_at'
                ]
            ]);

    }

    public function test_show_returns_specific_reservation()
    {

        $this->actingAs($this->user);

        $reservation = Reservation::factory()->create();

        $response = $this->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200)
            ->assertJson($reservation->toArray());
    }

    public function test_update_modifies_existing_reservation()
    {
        $this->actingAs($this->user);
        $reservation = Reservation::factory()->create();

        $updatedData = [
            'event_name' => 'Updated Event Name',
        ];

        $response = $this->putJson("/api/reservations/{$reservation->id}", $updatedData);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Reserva actualizada con éxito'])
            ->assertJsonStructure(['message', 'reservation']);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'event_name' => 'Updated Event Name',
        ]);
    }

    public function test_destroy_deletes_reservation()
    {
        $this->actingAs($this->user);
        $reservation = Reservation::factory()->create();

        $response = $this->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Reservation deleted successfully']);

        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_show_returns_404_for_non_existent_reservation()
    {
        $this->actingAs($this->user);
        $response = $this->getJson("/api/reservations/9999");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Reservation not found']);
    }

    public function test_destroy_returns_404_for_non_existent_reservation()
    {
        $this->actingAs($this->user);

        $response = $this->deleteJson("/api/reservations/9999");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Reservation not found']);
    }
}
