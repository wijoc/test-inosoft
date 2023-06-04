<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('mobil', function (Blueprint $collection) {
            // $collection->id();
            $collection->integer('mesin');
            $collection->integer('kapasitas_penumpang');
            $collection->string('tipe');
            $collection->integer('stok');
            $collection->string('kendaraan_id');
            $collection->timestamps();
            $collection->jsonSchema([
                'bsonType' => 'object',
                'required' => ['mesin', 'kapasitas_penumpang', 'tipe', 'stok', 'kendaraan_id', 'created_at'],
                'properties' => [
                    'mesin' => [
                        'bsonType' => 'string',
                        'description' => 'mesin field is required and value must be string (alphanumeric).'
                    ],
                    'kapasitas_penumpang' => [
                        'bsonType' => 'int',
                        'minimum' => 1,
                        'description' => 'kapasitas_penumpang field is required and value must be integer with minimum value is 1.'
                    ],
                    'tipe' => [
                        'bsonType' => 'string',
                        'description' => 'tipe field is required and value must be string (alphanumeric).'
                    ],
                    'stok' => [
                        'bsonType' => 'int',
                        'minimum' => 0,
                        'description' => 'stok field must be integer with minimum value is 0.'
                    ],
                    'kendaraan_id' => [
                        'bsonType' => 'string',
                        'description' => 'kendaraan_id field must be string (alphanumeric).'
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
        Schema::dropIfExists('mobil');
    }
}
