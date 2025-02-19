<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use \App\Models\User;
use \App\Models\Artist;

class ArtistControllerTest extends TestCase
{
    private $userToken;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');

        // Create admin user
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $this->user = $user;
        $this->userToken = $user->createToken('TestToken')->plainTextToken;
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    #[Test]
    public function test_that_index_returns_all_artists(): void
    {
        Artist::factory(2)->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->getJson('/api/artists');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $response->assertJsonStructure([['id', 'name']]);
    }

    #[Test]
    public function test_that_store_creates_a_new_artist(): void
    {
        $artist = [
            'name' => 'Test Artist',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/artists', $artist);

        $response->assertStatus(201);
        $response->assertJson($artist);
        $this->assertDatabaseHas('artists', ['name' => $artist['name']]);
    }

    #[Test]
    public function test_that_store_fails_without_name_data(): void
    {
        $artist = [];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/artists', $artist);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }
}