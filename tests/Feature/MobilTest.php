<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MobilTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIDKendaraanNotFound()
    {
        $userData = [
            'engine' => 1500,
            'passenger_capacity' => 5,
            'type' => 'SUV',
            'id_kendaraan' => 'asdasdasd'
        ];

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2JldGEvbG9naW4iLCJpYXQiOjE2ODU5MjkwOTcsImV4cCI6MzU3ODA4OTA5NywibmJmIjoxNjg1OTI5MDk3LCJqdGkiOiJhcW1BTFhJM0dVdFF5Ulk5Iiwic3ViIjoiNjQ3ZDNjN2M1NDRjMGFlNzhlMDhlNTBhIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsInR5cGUiOiJhY2Nlc3NfdG9rZW4ifQ.oeKQRYu-NG9TkJE_XALzm6deZw7Qm6714Wtjrpsf5Kk';

        $this->json('POST', '/api/beta/mobil', $userData, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token])
            ->assertStatus(400);
    }

    public function testCapacityGTEOne () {
        $userData = [
            'engine' => 1500,
            'passenger_capacity' => 0,
            'type' => 'SUV',
            'id_kendaraan' => '647d3c7c544c0ae78e08e515'
        ];

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2JldGEvbG9naW4iLCJpYXQiOjE2ODU5MjkwOTcsImV4cCI6MzU3ODA4OTA5NywibmJmIjoxNjg1OTI5MDk3LCJqdGkiOiJhcW1BTFhJM0dVdFF5Ulk5Iiwic3ViIjoiNjQ3ZDNjN2M1NDRjMGFlNzhlMDhlNTBhIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsInR5cGUiOiJhY2Nlc3NfdG9rZW4ifQ.oeKQRYu-NG9TkJE_XALzm6deZw7Qm6714Wtjrpsf5Kk';

        $this->json('POST', '/api/beta/mobil', $userData, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => [
                    'passenger_capacity' => ['Passenger capacity must be numeric and greater than or equal to 1.']
                ]
            ]);
    }
}
