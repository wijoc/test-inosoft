<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('motor', function (Blueprint $collection) {
            $collection->integer('mesin');
            $collection->string('tipe_suspensi');
            $collection->string('tipe_transmisi');
            $collection->integer('stok');
            $collection->string('kendaraan_id');
            $collection->timestamps();
            $collection->jsonSchema([
                'bsonType' => 'object',
                'required' => ['mesin', 'tipe_suspensi', 'tipe_transmisi', 'stok', 'kendaraan_id', 'created_at'],
                'properties' => [
                    'mesin' => [
                        'bsonType' => 'string',
                        'description' => 'mesin field is required and value must be string (alphanumeric).'
                    ],
                    'tipe_suspensi' => [
                        'bsonType' => [ 'telescopic', 'usd' ],
                        'description' => 'tipe_suspensi field is required and value must be one of the enum values.'
                    ],
                    'tipe_transmisi' => [
                        'bsonType' => [ 'manual', 'semi-manual', 'automatic' ],
                        'description' => 'tipe_transmisi field is required and value must be one of the enum values.'
                    ],
                    'stok' => [
                        'bsonType' => 'int',
                        'minimum' => 0,
                        'description' => 'stok field is required and value must be integer with minimum value is 0.'
                    ],
                    'kendaraan_id' => [
                        'bsonType' => 'string',
                        'description' => 'kendaraan_id field is required and value must be string (alphanumeric).'
                    ],
                    'created_at' => [
                        'bsonType' => 'date',
                        'description' => 'created_at field is required and value must be following ISO 8601 format.'
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
        Schema::dropIfExists('motor');
    }
}
