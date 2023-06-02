<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MotorController extends KendaraanController
{
    protected $motorData;

    public function __construct () {
        parent::__construct();
        $this->motorData = [
            'm1' => [
                'mesin' => '150',
                'tipe_suspensi' => 'Telescopic',
                'tipe_transmisi' => 'semi-manual',
                'kendaraan' => $this->data[1]
            ],
            'm2' => [
                'mesin' => '150',
                'tipe_suspensi' => 'Telescopic',
                'tipe_transmisi' => 'manual',
                'kendaraan' => $this->data[1]
            ],
            'm3' => [
                'mesin' => '250',
                'tipe_suspensi' => 'Telescopic',
                'tipe_transmisi' => 'manual',
                'kendaraan' => $this->data[3]
            ],
            'm4' => [
                'mesin' => '250',
                'tipe_suspensi' => 'USD',
                'tipe_transmisi' => 'manual',
                'kendaraan' => $this->data[4]
            ]
        ];

        $this->rules = [
            'mesin' => 'required|numeric|max:4',
            'suspensi' => ['required', Rule::in(["Telescopic","USD"])],
            'transmisi' => ['required', Rule::in(["automatic","semi-manual","manual"])],
            'id_kendaraan' => 'required' // Need to add exists rule
        ];

        $this->messages = [
            'mesin.required' => 'Mesin is required.',
            'mesin.numeric' => 'Mesin must be numeric.',
            'mesin.max' => 'Mesin length cannot be more than 4 digit.',
            'suspensi.required' => 'Suspensi is required.',
            'suspensi.in' => 'Suspensi value must be "Telescopic" or "USD".',
            'transmisi.required' => 'Transmisi is required.',
            'transmisi.in' => 'Transmisi value must be one of : "automatic", "semi-manual" or "manual".',
            'id_kendaraan.required' => 'ID Kendaraan is required.'
        ];
    }

    public function motorIndex ()
    {
        return response()->json([
            'success' => true,
            'message' => ($this->motorData && count($this->motorData) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $this->motorData ? count($this->motorData) : 0,
            'count_all' => $this->motorData ? count($this->motorData) : 0,
            'data' => $this->motorData ?? null
        ], 200);
    }

    public function motorStore (Request $request) {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $input = true;
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
            if ($this->motorData[$motor]) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $this->motorData[$motor]
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
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function motorUpdate (Request $request, String|Int $motor) {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $update = true;
            if ($update) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data updated.'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data.'
                ], 500);
            }
        }
    }

    public function motorDestroy (String|Int $motor) {
        if ($motor) {
            if ($this->motorData[$motor]) {
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
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }
}
