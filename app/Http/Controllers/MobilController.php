<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mobil;
use App\Http\Resources\MobilResource;

class MobilController extends KendaraanController
{
    protected $mobilModel;

    public function __construct () {
        parent::__construct();
        $this->mobilModel = new Mobil();

        $this->rules = [
            'engine' => 'required|numeric|gte:1',
            'passenger_capacity' => 'required|numeric|gte:1',
            'type' => 'required',
            'id_kendaraan' => 'required|exists:App\Models\Kendaraan,_id' // Need to add exists rule
        ];

        $this->messages = [
            'engine.required' => 'Engine is required.',
            'engine.numeric' => 'Engine must be numeric.',
            'engine.gte' => 'Engine must be numeric and greater than or equal to 1.',
            'passenger_capacity.required' => 'Passenger capacity is required.',
            'passenger_capacity.numeric' => 'Passenger capacity must be numeric.',
            'passenger_capacity.gte' => 'Passenger capacity must be numeric and greater than or equal to 1.',
            'type.required' => 'type is required.',
            'id_kendaraan.required' => 'ID Kendaraan is required.',
            'id_kendaraan.exists' => 'ID Kendaraan not found.'
        ];
    }

    public function mobilIndex () {
        // Get all Mobil
        $mobilData = $this->mobilModel->getMobils();

        return response()->json([
            'success' => true,
            'message' => ($mobilData && count($mobilData) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $mobilData ? count($mobilData) : 0,
            'count_all' => $mobilData ? count($mobilData) : 0,
            'data' => $mobilData ? MobilResource::collection($mobilData) : null
        ], 200);
    }

    public function mobilStore (Request $request) {
        $this->rules['stock'] = 'numeric|gte:0';
        $this->messages['stock.numeric'] = 'Stock value must be numeric.';
        $this->messages['stock.gte'] = 'Stock value must be numeric and greater than or equal to 0.';

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            // Set input data
            $inputData = [
                'mesin' => $validator->validated()['engine'],
                'kapasitas_penumpang' => $validator->validated()['passenger_capacity'],
                'tipe' => $validator->validated()['type'],
                'kendaraan_id' => $validator->validated()['id_kendaraan'],
                'stok' => $validator->validated()['stock'] ?? 0,
                'created_at' => Carbon::now()->timezone('UTC')->toIso8601String()
            ];

            $input = $this->mobilModel->insertMobil($inputData);

            if ($input) {
                return response()->json([
                    'success' => true,
                    'message' => 'Succesfully added new data.'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to added new data.'
                ], 500);
            }
        }
    }

    public function mobilShow (String|Int $mobil) {
        if ($mobil) {
            // Get Data
            $mobilData = $this->mobilModel->findMobil($mobil, 1);

            if ($mobilData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $mobilData ? MobilResource::make($mobilData) : null
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobil with given "id" '.$mobil.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function mobilUpdate (Request $request, String|Int $mobil) {
        if ($mobil) {
            // Check if data exists
            $check = $this->mobilModel->findMobil($mobil, 0, ['_id']);
            if ($check) {
                $validator = Validator::make($request->all(), $this->rules, $this->messages);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The given data was invalid',
                        'errors' => $validator->errors()
                    ], 400);
                } else {
                    // Set update data
                    $updateData = [
                        'mesin' => $validator->validated()['engine'],
                        'kapasitas_penumpang' => $validator->validated()['passenger_capacity'],
                        'tipe' => $validator->validated()['type'],
                        'kendaraan_id' => $validator->validated()['id_kendaraan'],
                        'updated_at' => Carbon::now()->timezone('UTC')->toIso8601String()
                    ];

                    $update = $this->mobilModel->updateMobil($updateData, $mobil);
                    if ($update) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Data updated.'
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to update data.'
                        ], 500);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobil with given "id" '.$mobil.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function mobilDestroy (String|Int $mobil) {
        if ($mobil) {
            // Check if data exists
            $check = $this->mobilModel->findMobil($mobil, 0, ['_id']);
            if ($check) {
                $delete = $this->mobilModel->deleteMobil($mobil);
                if ($delete) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data deleted.'
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data with given "id" '.$mobil.' not found.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobil with given "id" '.$mobil.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }
}
