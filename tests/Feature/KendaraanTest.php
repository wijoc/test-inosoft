<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KendaraanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiredField()
    {
        $userData = [
            'colour' => 'NewColor',
            'price' => '123456'
        ];
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2JldGEvbG9naW4iLCJpYXQiOjE2ODU5MjkwOTcsImV4cCI6MzU3ODA4OTA5NywibmJmIjoxNjg1OTI5MDk3LCJqdGkiOiJhcW1BTFhJM0dVdFF5Ulk5Iiwic3ViIjoiNjQ3ZDNjN2M1NDRjMGFlNzhlMDhlNTBhIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsInR5cGUiOiJhY2Nlc3NfdG9rZW4ifQ.oeKQRYu-NG9TkJE_XALzm6deZw7Qm6714Wtjrpsf5Kk';

        $this->json('POST', '/api/beta/kendaraan', $userData, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => [
                    'release_year' => ['Release Year is required.']
                ]
            ]);
    }

    public function testAuthorization () {
        $userData = [
            'year_release' => '2011',
            'colour' => 'NewColor',
            'price' => '123456'
        ];

        $this->json('POST', '/api/beta/kendaraan', $userData, ['Accept' => 'application/json'])
            ->assertStatus(401);
    }
}
