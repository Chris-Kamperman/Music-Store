<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    #[Test]
    public function test_that_registration_registers(): void
    {
        $testUser = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(201);
        $response->assertJsonStructure(['user', 'token']);
        $response->assertJson(['user' => ['name' => $testUser['name'], 'email' => $testUser['email'], 'is_admin' => $testUser['is_admin']]]);

        $this->assertDatabaseHas('users', ['email' => $testUser['email']]);
    }

    #[Test]
    public function test_that_registration_fails_without_name_data(): void
    {
        $testUser = [
            'email' => 'test@email.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    #[Test]
    public function test_that_registration_fails_without_email_data(): void
    {
        $testUser = [
            'name' => 'Test User',
            'password' => '12345',
            'password_confirmation' => '12345',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    #[Test]
    public function test_that_registration_fails_without_password_data(): void
    {
        $testUser = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password_confirmation' => '12345',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function test_that_registration_fails_without_password_confirmation_data(): void
    {
        $testUser = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '12345',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function test_that_registration_fails_with_wrong_password_confirmation_data(): void
    {
        $testUser = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '12345',
            'password_confirmation' => '678910',
            'is_admin' => false,
        ];

        $response = $this->postJson('/api/register', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function test_that_logout_logs_out(): void
    {
        // Create a user
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out']);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

    #[Test]
    public function test_that_login_logs_in(): void
    {
        // Create a user
        $user = User::factory()->create();

        $testUser = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $testUser);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
        $response->assertJson(['user' => ['name' => $user->name, 'email' => $user->email, 'is_admin' => $user->is_admin]]);
    }

    #[Test]
    public function test_that_login_fails_without_email_data(): void
    {
        // Create a user
        $user = User::factory()->create();

        $testUser = [
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    #[Test]
    public function test_that_login_fails_without_password_data(): void
    {
        // Create a user
        $user = User::factory()->create();

        $testUser = [
            'email' => $user->email,
        ];

        $response = $this->postJson('/api/login', $testUser);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function test_that_login_fails_with_wrong_password_data(): void
    {
        // Create a user
        $user = User::factory()->create();

        $testUser = [
            'email' => $user->email,
            'password' => 'wrong_password',
        ];

        $response = $this->postJson('/api/login', $testUser);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials']);
    }
}


