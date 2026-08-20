<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);

        $postResponse = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'StrongP@ssw0rd123',
            'password_confirmation' => 'StrongP@ssw0rd123',
        ]);
        $postResponse->assertStatus(404);
        $this->assertGuest();
    }
}
