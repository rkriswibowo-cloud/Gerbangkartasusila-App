<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\DestinationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_read_tickets_or_change_profiles(): void
    {
        $user = User::factory()->create();
        $this->getJson('/api/tickets')->assertUnauthorized();
        $this->get('/api/tickets')->assertUnauthorized();
        $this->getJson('/api/tickets/1')->assertUnauthorized();
        $this->postJson('/api/tickets', [])->assertUnauthorized();
        $this->postJson('/api/profile/update', ['name' => 'Changed', 'email' => 'changed@example.com'])->assertUnauthorized();
        $this->assertSame($user->name, $user->fresh()->name);
    }

    public function test_registration_login_profile_and_logout_with_real_tokens(): void
    {
        $this->postJson('/api/register', ['name' => 'Traveler', 'email' => 'traveler@example.com', 'password' => 'secret1234'])
            ->assertCreated()->assertJsonStructure(['access_token', 'data' => ['id']]);
        $login = $this->postJson('/api/login', ['email' => 'traveler@example.com', 'password' => 'secret1234'])->assertOk();
        $headers = ['Authorization' => 'Bearer '.$login->json('access_token')];
        $this->getJson('/api/profile', $headers)->assertOk()->assertJsonPath('data.email', 'traveler@example.com');
        $this->postJson('/api/profile/update', ['name' => 'Updated', 'email' => 'traveler@example.com'], $headers)
            ->assertOk()->assertJsonPath('data.name', 'Updated');
        $this->postJson('/api/logout', [], $headers)->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_ticket_prices_ownership_and_pending_payment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $category = DestinationCategory::create(['name' => 'Nature', 'slug' => 'nature']);
        $destination = Destination::create(['category_id' => $category->id, 'name' => 'Test', 'slug' => 'test', 'description' => 'Test', 'address' => 'Test', 'ticket_price' => 15000, 'status' => 'published']);
        $this->actingAs($owner, 'sanctum');
        $payload = ['destination_id' => $destination->id, 'visitor_name' => 'Visitor', 'visitor_email' => 'visitor@example.com', 'visitor_phone' => '0800000000', 'visit_date' => now()->addDay()->toDateString(), 'quantity' => 2, 'total_price' => 1, 'status' => 'paid'];
        $ticket = $this->postJson('/api/tickets', $payload)->assertCreated()->assertJsonPath('data.status', 'pending')->assertJsonPath('data.user_id', $owner->id);
        $this->assertEquals(30000, $ticket->json('data.total_price'));
        $this->getJson('/api/tickets')->assertJsonCount(1, 'data');
        $this->getJson('/api/tickets/'.$ticket->json('data.ticket_code'))->assertOk();
        $this->postJson('/api/tickets', array_merge($payload, ['visit_date' => '2000-01-01']))->assertUnprocessable();
        $this->actingAs($other, 'sanctum');
        $this->getJson('/api/tickets')->assertJsonCount(0, 'data');
        $this->getJson('/api/tickets/'.$ticket->json('data.id'))->assertNotFound();
        $this->getJson('/api/tickets/'.$ticket->json('data.ticket_code'))->assertNotFound();
    }

    public function test_public_catalog_endpoints_and_media_boundary(): void
    {
        foreach (['destinations', 'events', 'hotels', 'culinary', 'articles'] as $endpoint) {
            $this->getJson('/api/'.$endpoint)->assertOk()->assertJsonStructure(['data']);
        }
        $this->getJson('/api/media/../../../.env')->assertNotFound();
        $this->getJson('/api/media/missing.png')->assertNotFound();
    }

    public function test_favorites_are_idempotent_and_private(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $category = DestinationCategory::create(['name' => 'Nature', 'slug' => 'nature']);
        $destination = Destination::create(['category_id' => $category->id, 'name' => 'Test', 'slug' => 'test', 'description' => 'Test', 'address' => 'Test', 'status' => 'published']);
        $this->actingAs($owner, 'sanctum');
        $favorite = $this->postJson('/api/favorites', ['destination_id' => $destination->id])->assertOk()->json('data.id');
        $this->postJson('/api/favorites', ['destination_id' => $destination->id])->assertOk();
        $this->getJson('/api/favorites')->assertJsonCount(1, 'data');
        $this->actingAs($other, 'sanctum');
        $this->getJson('/api/favorites')->assertJsonCount(0, 'data');
        $this->deleteJson('/api/favorites/'.$favorite)->assertNotFound();
        $this->actingAs($owner, 'sanctum');
        $this->deleteJson('/api/favorites/'.$favorite)->assertOk();
        $this->assertDatabaseCount('favorites', 0);
    }

    public function test_regular_and_disabled_users_cannot_enter_admin_panel(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $panel = \Filament\Panel::make()->id('admin');
        $this->assertFalse($user->canAccessPanel($panel));
        \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
        $user->assignRole('super_admin');
        $this->assertTrue($user->canAccessPanel($panel));
        $user->update(['status' => 'inactive', 'password' => Hash::make('secret1234')]);
        $this->assertFalse($user->canAccessPanel($panel));
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'secret1234'])->assertUnauthorized();
    }
}
