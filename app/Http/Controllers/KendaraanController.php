<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    protected $data;
    protected $rules;
    protected $messages;

    public function __construct () {
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
            'tahun' => 'required|numeric|max:4',
            'warna' => 'required',
            'harga' => 'required|numeric|between:0,9999999999999.99'
        ];

        $this->messages = [
            'tahun.required' => 'Tahun Keluaran is required.',
            'tahun.numeric' => 'Tahun Keluaran must be numeric.',
            'tahun.max' => 'Tahun Keluaran length cannot be more than 4 digit.',
            'warna.required' => 'Warna is required.',
            'harga.required' => 'Harga is required.',
            'harga.numeric' => 'Harga must be numeric.',
            'harga.numeric' => 'Harga must be numeric with value between 0 to 9,999,999,999,999.99',
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function show(String|Int $kendaraan)
    {
        if ($kendaraan) {
            if ($this->data[$kendaraan]) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $this->data[$kendaraan]
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function destroy(String|Int  $kendaraan)
    {
        if ($kendaraan) {
            if ($this->data[$kendaraan]) {
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
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }
}
