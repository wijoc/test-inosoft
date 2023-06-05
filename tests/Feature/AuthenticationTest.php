<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPasswordNotMatch()
    {
        $userData = [
            'username' => 'register1',
            'password' => '12345678',
            'password_confirmation' => 'asdasdas',
        ];

        $this->json('POST', '/api/beta/registration', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400);
    }
}
