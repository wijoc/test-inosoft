<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKendaraanCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('kendaraan', function (Blueprint $collection) {
            // $collection->id();
            $collection->integer('tahun_keluaran');
            $collection->string('warna');
            $collection->decimal('harga', $precision = 13, $scale = 2);
            $collection->timestamps();
            $collection->jsonSchema([
                'bsonType' => 'object',
                'required' => ['tahun_keluaran', 'warna', 'harga', 'created_at'],
                'properties' => [
                    'tahun_keluaran' => [
                        'bsonType' => 'string',
                        'pattern' => '^(\\d{4})$',
                        'description' => 'tahun_keluaran field is required and value must valid year format YYYY.'
                    ],
                    'warna' => [
                        'bsonType' => 'string',
                        'description' => 'warna field is required and value must be string (alphanumeric).'
                    ],
                    'harga' => [
                        'bsonType' => 'number',
                        'minimum' => 0,
                        'maximum' => 9999999999.99,
                        'multipleOf' => 0.01,
                        'description' => 'harga field must be integer with minimum value is 0, maximum value is 9999999999999.99, with 10 digit number and 2 digit decimal.'
                    ],
                    'created_at' => [
                        'bsonType' => 'date',
                        'description' => 'Field must be following ISO 8601 format.'
                    ]
                ],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kendaraan');
    }
}
