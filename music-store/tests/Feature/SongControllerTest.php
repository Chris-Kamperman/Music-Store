<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use \App\Models\User;
use \App\Models\Album;
use \App\Models\Song;

class SongControllerTest extends TestCase
{
    private $userToken;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        Storage::fake('local');

        // Create admin user
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $this->user = $user;
        $this->userToken = $user->createToken('TestToken')->plainTextToken;

        // Create Artist
        Album::factory()->create();
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    #[Test]
    public function test_that_store_stores_song(): void
    {
        $song = [
            'title' => 'Test Song',
            'album_id' => 1,
            'file' => UploadedFile::fake()->create('test.mp3'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/songs', $song);

        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'title', 'album_id', 'file']);
        $this->assertDatabaseHas('songs', ['title' => $song['title']]);
        Storage::disk('local')->assertExists($response->json()['file']);
    }

    #[Test]
    public function test_that_store_fails_without_title(): void
    {
        $song = [
            'album_id' => 1,
            'file' => UploadedFile::fake()->create('test.mp3'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/songs', $song);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    #[Test]
    public function test_that_store_fails_without_album_id(): void
    {
        $song = [
            'title' => 'Test Song',
            'file' => UploadedFile::fake()->create('test.mp3'),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/songs', $song);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('album_id');
    }

    #[Test]
    public function test_that_store_fails_without_file(): void
    {
        $song = [
            'title' => 'Test Song',
            'album_id' => 1,
        ];


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/songs', $song);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    #[Test]
    public function test_that_store_fails_with_wrong_filetype(): void
    {
        $song = [
            'title' => 'Test Song',
            'album_id' => 1,
            'file' => UploadedFile::fake()->create('test.pdf'),
        ];


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->postJson('/api/songs', $song);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    #[Test]
    public function test_that_download_downloads_song(): void
    {
        $songUrl = Storage::disk('local')->put('songs/', UploadedFile::fake()->create('test.mp3'));
        $song = Song::create([
            'title' => 'Test Song',
            'album_id' => 1,
            'file' => $songUrl,
        ]);

        $this->user->albums()->attach(1);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->get('/api/songs/' . $song->id . '/download');

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', "attachment; filename=\"" . $song['title'] . ".mp3\"");
        $response->assertHeader('Content-Type', 'audio/mpeg');
    }

    #[Test]
    public function test_that_download_non_existing_file_fails(): void
    {
        $song = Song::create([
            'title' => 'Test Song',
            'album_id' => 1,
            'file' => 'non-existing-file.mp3',
        ]);

        $this->user->albums()->attach(1);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)->get('/api/songs/' . $song->id . '/download');

        $response->assertStatus(404);
        $response->assertSeeText('File not found');
    }
}