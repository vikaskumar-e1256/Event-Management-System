<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Response;
use function Pest\Laravel\{assertDatabaseHas, post};
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('redirects to dashboard for role 1 user (organizer)', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::ORGANIZER,
        'password' => bcrypt('password123')
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $adminUser->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $responseData = $response->json();
    expect($responseData['success'])->toBe(true);
    expect($responseData['redirect'])->toBe(route('dashboard'));
});

it('redirects to home for role 0 user (attendee)', function () {
    $attendee = User::factory()->create([
        'role' => UserRole::ATTENDEE,
        'password' => bcrypt('password123')
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $attendee->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $responseData = $response->json();
    expect($responseData['success'])->toBe(true);
    expect($responseData['redirect'])->toBe(route('home'));
});

it('fails login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson(route('login'), [
        'email' => 'testuser@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $responseData = $response->json();

    expect($responseData['success'])->toBe(false);
    expect($responseData['message'])->toContain('Invalid', 'credentials!');
});


it('creates a new user during registration', function () {
    $data = [
        'name' => 'Vikas Kumar',
        'email' => 'vikaskumar.e1256@gmail.com',
        'password' => 'password123',
    ];

    $response = $this->postJson(route('register'), $data);

    $response->assertStatus(Response::HTTP_OK);

    $responseData = $response->json();
    expect($responseData['message'])->toBe('User registered successfully!');

    assertDatabaseHas('users', [
        'email' => $data['email'],
    ]);
});

it('returns an error if email already exists during registration', function () {
    $existingUser = User::factory()->create([
        'email' => 'vikaskumar.e1256@gmail.com',
    ]);

    $data = [
        'name' => 'Vikas Kumar',
        'email' => 'vikaskumar.e1256@gmail.com',
        'password' => 'password123',
    ];

    $response = $this->postJson(route('register'), $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $responseData = $response->json();
    expect($responseData['errors'])->toHaveKey('email');
    expect($responseData['errors']['email'][0])->toBe('The email has already been taken.');
});

it('logs out the user', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123')
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $logoutResponse = post('/logout');

    expect($logoutResponse->status())->toBe(302);

    $this->assertGuest();
});
