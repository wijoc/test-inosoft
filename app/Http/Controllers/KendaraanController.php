<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    protected $data;
    protected $rules;
    protected $messages;
    protected $kendaraanModel;

    public function __construct () {
        $this->kendaraanModel = new Kendaraan();
        $this->data = [
            '1' => [
                'tahun_keluaran' => '2000',
                'warna' => 'Merah',
                'harga' => '1000000.99'
            ],
            '2' => [
                'tahun_keluaran' => '2000',
                'warna' => 'Jingga',
                'harga' => '1200000.99'
            ],
            '3' => [
                'tahun_keluaran' => '2001',
                'warna' => 'Merah',
                'harga' => '2000000.99'
            ],
            '4' => [
                'tahun_keluaran' => '2001',
                'warna' => 'Kuning',
                'harga' => '20000000'
            ],
            '5' => [
                'tahun_keluaran' => '2010',
                'warna' => 'Putih',
                'harga' => '5000000000'
            ],
            '6' => [
                'tahun_keluaran' => '2020',
                'warna' => 'Merah',
                'harga' => '6250719854.00'
            ],
            '7' => [
                'tahun_keluaran' => '2003',
                'warna' => 'Silver',
                'harga' => '240000000'
            ]
        ];

        $this->rules = [
            'release_year' => 'required|numeric|gt:0|digits:4',
            'colour' => 'required',
            'price' => 'required|numeric|between:0,9999999999999.99'
        ];

        $this->messages = [
            'release_year.required' => 'Release Year is required.',
            'release_year.numeric' => 'Release Year must be numeric.',
            'release_year.gt' => 'Release Year must be greater than 0.',
            'release_year.digits' => 'Release Year length cannot be more than 4 digit.',
            'colour.required' => 'Colour is required.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be numeric.',
            'price.numeric' => 'Price must be numeric with value between 0 to 9,999,999,999,999.99',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => ($this->data && count($this->data) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $this->data ? count($this->data) : 0,
            'count_all' => $this->data ? count($this->data) : 0,
            'data' => $this->data ?? null
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            // Set input data, use iso-8601 format in UTC
            $inputData = [
                'tahun_keluaran' => $validator->validated()['release_year'],
                'warna' => $validator->validated()['colour'],
                'harga' => $validator->validated()['price'],
                'created_at' => Carbon::now()->timezone('UTC')->toIso8601String()
            ];

            // Input to database
            $input = $this->kendaraanModel->insertKendaraan($inputData);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function show(String|Int $kendaraan)
    {
        if ($kendaraan) {
            // Get Data
            $data = $this->kendaraanModel->findKendaraan($kendaraan);

            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendaraan with given "id" '.$kendaraan.' not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String|Int $kendaraan)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            // Check if data exists
            $check = $this->kendaraanModel->checkKendaraan($kendaraan);

            if ($check) {
                // Set update data, use iso-8601 format in UTC
                $updateData = [
                    'tahun_keluaran' => $validator->validated()['release_year'],
                    'warna' => $validator->validated()['colour'],
                    'harga' => $validator->validated()['price'],
                    'updated_at' => Carbon::now()->timezone('UTC')->toIso8601String()
                ];

                $update = $this->kendaraanModel->updateKendaraan($updateData, $kendaraan);

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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendaraan with given "id" '.$kendaraan.' not found.'
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function destroy(String|Int  $kendaraan)
    {
        if ($kendaraan) {
            // Check if data exists
            $check = $this->kendaraanModel->checkKendaraan($kendaraan);

            $delete = $this->kendaraanModel->deleteKendaraan($kendaraan);
            if ($check) {
                if ($delete) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data deleted.'
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kendaraan with given "id" '.$kendaraan.' not found.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendaraan with given "id" '.$kendaraan.' not found.'
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
