<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use \App\Models\User;
use \App\Models\Album;
use \App\Models\Artist;

class AlbumControllerTest extends TestCase
{
    private $userToken;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        Storage::fake('public');

        // Create admin user
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $this->user = $user;
        $this->userToken = $user->createToken('TestToken')->plainTextToken;

        // Create Artist
        Artist::factory()->create();
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    #[Test]
    public function test_that_index_returns_all_albums(): void
    {
        Album::factory(2)->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->getJson('/api/albums');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $response->assertJsonStructure([['id', 'title', 'artist_id', 'artwork', 'genre', 'artist', 'songs']]);
    }

    #[Test]
    public function test_that_store_creates_a_new_album(): void
    {
        $album = [
            'title' => 'Test Album',
            'artist_id' => 1,
            'genre' => 'Test Genre',
            'artwork' => UploadedFile::fake()->create('test.jpg'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(201);
        $response->assertJson(['title' => $album['title'], 'artist_id' => $album['artist_id'], 'genre' => $album['genre']]);
        $this->assertDatabaseHas('albums', ['title' => $album['title']]);
        Storage::disk('public')->assertExists($response->json()['artwork']);
    }

    #[Test]
    public function test_that_store_fails_without_title(): void
    {
        $album = [
            'artist_id' => 1,
            'genre' => 'Test Genre',
            'artwork' => UploadedFile::fake()->create('test.jpg'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    #[Test]
    public function test_that_store_fails_without_artist(): void
    {
        $album = [
            'title' => 'Test Album',
            'genre' => 'Test Genre',
            'artwork' => UploadedFile::fake()->create('test.jpg'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('artist_id');
    }

    #[Test]
    public function test_that_store_fails_without_genre(): void
    {
        $album = [
            'title' => 'Test Album',
            'artist_id' => 1,
            'artwork' => UploadedFile::fake()->create('test.jpg'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('genre');
    }

    #[Test]
    public function test_that_store_fails_without_artwork(): void
    {
        $album = [
            'title' => 'Test Album',
            'artist_id' => 1,
            'genre' => 'Test Genre',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('artwork');
    }

    #[Test]
    public function test_that_store_fails_with_wrong_artwork_filetype(): void
    {
        $album = [
            'title' => 'Test Album',
            'artist_id' => 1,
            'genre' => 'Test Genre',
            'artwork' => UploadedFile::fake()->create('test.pdf'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums', $album);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('artwork');
    }

    #[Test]
    public function test_that_show_shows_correct_album(): void
    {
        $album = Album::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->getJson('/api/albums/' . $album->id);

        $response->assertStatus(200);
        $response->assertJson(['id' => $album->id, 'title' => $album->title, 'artist_id' => $album->artist_id, 'genre' => $album->genre, 'artwork' => $album->artwork, 'artist' => [], 'songs' => [], 'owned' => false]);
    }

    #[Test]
    public function test_that_show_non_existing_album_returns_error(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->getJson('/api/albums/' . 1);

        $response->assertStatus(404);
    }

    #[Test]
    public function test_that_buyAlbum_adds_album_to_user_collection(): void
    {
        $album = Album::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/albums/' . $album->id . '/purchase');

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_album', ['user_id' => $this->user->id, 'album_id' => $album->id]);
    }

    #[Test]
    public function test_that_getUserAlbums_returns_all_user_albums(): void
    {
        $albums = Album::factory(2)->create();
        foreach ($albums as $album) {
            $this->user->albums()->attach($album->id);
        }

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->getJson('/api/user/albums');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $response->assertJsonStructure([['id', 'title', 'artist_id', 'artwork', 'genre', 'artist', 'songs']]);
    }
}
