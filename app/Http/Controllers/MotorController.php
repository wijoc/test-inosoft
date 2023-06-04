<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Motor;
use App\Http\Resources\MotorResource;

class MotorController extends KendaraanController
{
    protected $motorModel;

    public function __construct () {
        parent::__construct();
        $this->motorModel = new Motor();

        $this->rules = [
            'engine' => 'required|numeric|gt:0',
            'suspension' => ['required', Rule::in(["telescopic","usd"])],
            'transmission' => ['required', Rule::in(["automatic","semi-manual","manual"])],
            'id_kendaraan' => 'required|exists:App\Models\Kendaraan,_id' // Need to add exists rule
        ];

        $this->messages = [
            'engine.required' => 'Engine is required.',
            'engine.numeric' => 'Engine must be numeric.',
            'engine.gt' => 'Engine must be greater than 0.',
            'suspension.required' => 'Suspension is required.',
            'suspension.in' => 'Suspension value must be "Telescopic" or "USD".',
            'transmission.required' => 'Transmission is required.',
            'transmission.in' => 'Transmission value must be one of : "automatic", "semi-manual" or "manual".',
            'id_kendaraan.required' => 'ID Kendaraan is required.',
            'id_kendaraan.exists' => 'ID Kendaraan not found.'
        ];
    }

    protected function prepareForValidation (Request $request) {
        $request->merge([
            'suspension' => strtolower($request->suspension),
            'transmission' => strtolower($request->transmission)
        ]);
    }

    public function motorIndex ()
    {
        // Get all Motor
        $motorData = $this->motorModel->getMotors();

        return response()->json([
            'success' => true,
            'message' => ($motorData && count($motorData) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $motorData ? count($motorData) : 0,
            'count_all' => $motorData ? count($motorData) : 0,
            'data' => $motorData ? MotorResource::collection($motorData) : null
        ], 200);
    }

    public function motorStore (Request $request) {
        $this->rules['stock'] = 'numeric|gte:0';
        $this->messages['stock.numeric'] = 'Stock value must be numeric.';
        $this->messages['stock.gte'] = 'Stock value must be numeric and greater than or equal to 0.';

        // Intercept and change request
        $this->prepareForValidation($request);
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
                'tipe_suspensi' => $validator->validated()['suspension'],
                'tipe_transmisi' => $validator->validated()['transmission'],
                'kendaraan_id' => $validator->validated()['id_kendaraan'],
                'stok' => intval($validator->validated()['stock']) ?? 0,
                'created_at' => Carbon::now()->timezone('UTC')->toIso8601String()
            ];

            $input = $this->motorModel->insertMotor($inputData);

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

    public function motorShow (String|Int $motor) {
        if ($motor) {
            // Get Data
            $motorData = $this->motorModel->findMotor($motor, 1);

            if ($motorData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $motorData ? MotorResource::make($motorData) : null
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor with given "id" '.$motor.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function motorUpdate (Request $request, String|Int $motor) {
        if ($motor) {
            // Check if data exists
            $check = $this->motorModel->findMotor($motor, 0, ['_id']);
            if ($check) {
                // Intercept and change request
                $this->prepareForValidation($request);
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
                        'tipe_suspensi' => $validator->validated()['suspension'],
                        'tipe_transmisi' => $validator->validated()['transmission'],
                        'kendaraan_id' => $validator->validated()['id_kendaraan'],
                        'updated_at' => Carbon::now()->timezone('UTC')->toIso8601String()
                    ];

                    $update = $this->motorModel->updateMotor($updateData, $motor);
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
                    'message' => 'Motor with given "id" '.$motor.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function motorDestroy (String|Int $motor) {
        if ($motor) {
            // Check if data exists
            $check = $this->motorModel->findMotor($motor, 0, ['_id']);
            if ($check) {
                $delete = $this->motorModel->deleteMotor($motor);
                if ($delete) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data deleted.'
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data with given "id" '.$motor.' not found.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor with given "id" '.$motor.' not found.'
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
