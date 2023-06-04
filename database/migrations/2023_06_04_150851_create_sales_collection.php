<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('sales', function (Blueprint $collection) {
            // $collection->id();
            $collection->timestamps('sales_date');
            $collection->integer('sales_qty_kendaraan');
            $collection->string('kendaraan_id');
            $collection->decimal('sales_total', $precision = 13, $scale = 2);
            $collection->json('sales_detail');
            $collection->timestamps();
            $collection->jsonSchema([
                'bsonType' => 'object',
                'required' => ['sales_date', 'sales_qty_kendaraan', 'kendaraan_id', 'sales_total', 'sales_detail', 'created_at'],
                'properties' => [
                    'sales_date' => [
                        'bsonType' => 'date',
                        'description' => 'sales_date field must be following ISO 8601 format.'
                    ],
                    'sales_qty_kendaraan' => [
                        'bsonType' => 'int',
                        'minimum' => 1,
                        'description' => 'sales_qty_kendaraan field must be integer with minimum value is 1.'
                    ],
                    'tahun_keluaran' => [
                        'bsonType' => 'string',
                        'pattern' => '^(\\d{4})$',
                        'description' => 'tahun_keluaran field is required and value must valid year format YYYY.'
                    ],
                    'kendaraan_id' => [
                        'bsonType' => 'string',
                        'description' => 'warna field is required and value must be string (alphanumeric).'
                    ],
                    'sales_total' => [
                        'bsonType' => 'number',
                        'minimum' => 0,
                        'maximum' => 9999999999.99,
                        'multipleOf' => 0.01,
                        'description' => 'sales_total field must be integer with minimum value is 0, maximum value is 9999999999999.99, with 10 digit number and 2 digit decimal.'
                    ],
                    'sales_detail' => [
                        'bsonType' => 'array',
                        'items' => [
                            'bsonType' => 'object',
                            'required' => ['type', 'qty', 'harga', 'subtotal'],
                            'properties' => [
                                'type' => [
                                    'bsonType' => [ 'motor', 'mobil' ],
                                    'description' => 'type field is required and value must be one of the enum values.'
                                ],
                                'qty' => [
                                    'bsonType' => 'int',
                                    'minimum' => 1,
                                    'description' => 'qty field must be integer with minimum value is 1.'
                                ],
                                'harga' => [
                                    'bsonType' => 'number',
                                    'minimum' => 0,
                                    'maximum' => 9999999999.99,
                                    'multipleOf' => 0.01,
                                    'description' => 'harga field must be integer with minimum value is 0, maximum value is 9999999999999.99, with 10 digit number and 2 digit decimal.'
                                ],
                                'subtotal' => [
                                    'bsonType' => 'number',
                                    'minimum' => 0,
                                    'maximum' => 9999999999.99,
                                    'multipleOf' => 0.01,
                                    'description' => 'subtotal field must be integer with minimum value is 0, maximum value is 9999999999999.99, with 10 digit number and 2 digit decimal.'
                                ],
                                'motor_id' => [
                                    'bsonType' => 'string',
                                    'description' => 'motor_id field is required and value must be string (alphanumeric).'
                                ],
                                'mobil_id' => [
                                    'bsonType' => 'string',
                                    'description' => 'motor_id field is required and value must be string (alphanumeric).'
                                ]
                            ]
                        ],
                        'if' => [
                            'properties' => [
                                'type' => [ 'const' => 'motor' ]
                            ]
                        ],
                        'then' => [
                            'required' => ['motor_id']
                        ],
                        'else' => [
                            'required' => ['mobil_id']
                        ]
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
        Schema::dropIfExists('sales');
    }
}
