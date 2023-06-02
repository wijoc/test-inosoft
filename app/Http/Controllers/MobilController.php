<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobilController extends KendaraanController
{
    protected $mobilData;

    public function __construct () {
        parent::__construct();
        $this->mobilData = [
            'mbl1' => [
                'mesin' => '8000',
                'kapasitas_penumpang' => 2,
                'tipe' => 'super sport',
                'kendaraan' => $this->data[5]
            ],
            'mbl2' => [
                'mesin' => '6500',
                'kapasitas_penumpang' => 2,
                'tipe' => 'super sport',
                'kendaraan' => $this->data[6]
            ],
            'mbl3' => [
                'mesin' => '1400',
                'kapasitas_penumpang' => 6,
                'tipe' => 'MPV',
                'kendaraan' => $this->data[7]
            ]
        ];

        $this->rules = [
            'mesin' => 'required|numeric|max:4',
            'kapasitas' => 'required|numeric|min:0',
            'tipe' => 'required',
            'id_kendaraan' => 'required' // Need to add exists rule
        ];

        $this->messages = [
            'mesin.required' => 'Mesin is required.',
            'mesin.numeric' => 'Mesin must be numeric.',
            'mesin.max' => 'Mesin length cannot be more than 4 digit.',
            'kapasitas.required' => 'Kapasitas is required.',
            'kapasitas.numeric' => 'Kapasitas must be numeric.',
            'kapasitas.min' => 'Kapasitas must be numeric and greater than 0.',
            'tipe.required' => 'Tipe is required.',
            'id_kendaraan.required' => 'ID Kendaraan is required.'
        ];
    }

    public function mobilIndex()
    {
        return response()->json([
            'suess' => true,
            'message' => ($this->mobilData && count($this->mobilData) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $this->mobilData ? count($this->mobilData) : 0,
            'count_all' => $this->mobilData ? count($this->mobilData) : 0,
            'data' => $this->mobilData ?? null
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

    public function motorShow (String|Int $mobil) {
        if ($mobil) {
            if ($this->mobilData[$mobil]) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $this->mobilData[$mobil]
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
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function motorUpdate (Request $request, String|Int $mobil) {
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

    public function motorDestroy (String|Int $mobil) {
        if ($mobil) {
            if ($this->mobilData[$mobil]) {
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
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }
}
