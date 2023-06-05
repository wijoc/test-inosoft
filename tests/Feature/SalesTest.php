<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStock()
    {
        $userData = [
            'sale_trans_date' => '2023-06-03T23:34:30+00:00',
            'id_kendaraan' => 'random',
            'sale_trans_detail' => [
                [
                    'type' => 'motor',
                    'qty' => 13,
                    'motor_id' => '647d484326ed6f603904af82',
                    'mobil_id' => '647b26d89faeab3d540c93d3'
                ],
                [
                    'type' => 'mobil',
                    'qty' => 1,
                    'motor_id' => '647afe27c7b0fdd5c503d1b7',
                    'mobil_id' => '647d487026ed6f603904af83'
                ]
            ],
        ];

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2JldGEvbG9naW4iLCJpYXQiOjE2ODU5MjkwOTcsImV4cCI6MzU3ODA4OTA5NywibmJmIjoxNjg1OTI5MDk3LCJqdGkiOiJhcW1BTFhJM0dVdFF5Ulk5Iiwic3ViIjoiNjQ3ZDNjN2M1NDRjMGFlNzhlMDhlNTBhIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsInR5cGUiOiJhY2Nlc3NfdG9rZW4ifQ.oeKQRYu-NG9TkJE_XALzm6deZw7Qm6714Wtjrpsf5Kk';

        $this->json('POST', '/api/beta/sales', $userData, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token])
            ->assertStatus(400);
    }

    public function testKendaraanID () {
        $userData = [
            'sale_trans_date' => '2023-06-03T23:34:30+00:00',
            'id_kendaraan' => 'random',
            'sale_trans_detail' => [
                [
                    'type' => 'motor',
                    'qty' => 13,
                    'motor_id' => '647d484326ed6f603904af82',
                    'mobil_id' => '647b26d89faeab3d540c93d3'
                ],
                [
                    'type' => 'mobil',
                    'qty' => 1,
                    'motor_id' => '647afe27c7b0fdd5c503d1b7',
                    'mobil_id' => '647d487026ed6f603904af83'
                ]
            ],
        ];

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2JldGEvbG9naW4iLCJpYXQiOjE2ODU5MjkwOTcsImV4cCI6MzU3ODA4OTA5NywibmJmIjoxNjg1OTI5MDk3LCJqdGkiOiJhcW1BTFhJM0dVdFF5Ulk5Iiwic3ViIjoiNjQ3ZDNjN2M1NDRjMGFlNzhlMDhlNTBhIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsInR5cGUiOiJhY2Nlc3NfdG9rZW4ifQ.oeKQRYu-NG9TkJE_XALzm6deZw7Qm6714Wtjrpsf5Kk';

        $this->json('POST', '/api/beta/sales', $userData, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token])
            ->assertStatus(400);
    }
}
