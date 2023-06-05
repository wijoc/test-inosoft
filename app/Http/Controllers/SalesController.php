<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Kendaraan;
use App\Models\Motor;
use App\Models\Mobil;
use App\Http\Resources\SalesResource;

class SalesController extends Controller
{
    protected $salesModel;
    protected $kendaraanModel;
    protected $motorModel;
    protected $mobilModel;

    public function __construct () {
        $this->kendaraanModel = new Kendaraan();
        $this->salesModel = new Sales();
        $this->motorModel = new Motor();
        $this->mobilModel = new Mobil();
    }

    public function index (Request $request) {
        $validator = Validator::make($request->all(), [
                'date_start' => 'date_format:c,Y-m-d\TH:i:sP',
                'date_end' => 'date_format:c,Y-m-d\TH:i:sP',
            ],[
                'date_start.date_format' => 'Sales date (sales_trans_date) must using ISO 8601.',
                'date_end.date_format' => 'Sales date (sales_trans_date) must using ISO 8601.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else if (!$request->date_start || !$request->date_end) {
            $filter['date_begin'] = Carbon::now()->subDays(30)->timezone('UTC')->toIso8601String();
            $filter['date_end'] = Carbon::now()->timezone('UTC')->toIso8601String();
        } else {
            $filter['date_begin'] = Carbon::parse($validator->validated()['date_start'])->timezone('UTC')->toIso8601String();
            $filter['date_end'] = Carbon::parse($validator->validated()['date_end'])->timezone('UTC')->toIso8601String();
        }

        $data = $this->salesModel->getSales($filter);

        return response()->json([
            'success' => true,
            'message' => ($data && count($data) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $data ? count($data) : 0,
            'count_all' => $data ? count($data) : 0,
            'data' => $data ? SalesResource::collection($data) : null
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
                'sale_trans_date' => 'required|date_format:c,Y-m-d\TH:i:sP',
                'id_kendaraan' => 'required|exists:App\Models\Kendaraan,_id',
                'sale_trans_detail' => 'required|array',
                'sale_trans_detail.*.type' => ['required', Rule::in(["motor","mobil"])],
                'sale_trans_detail.*.qty' => 'required|numeric|gt:0',
                'sale_trans_detail.*.motor_id' => [
                    'required_if:sale_trans_detail.*.type,motor',
                    Rule::when(
                        $request['sale_trans_detail.*.type'] === 'motor',
                        ['exists:App\Models\Motor,_id']
                    )
                ],
                'sale_trans_detail.*.mobil_id' => [
                    'required_if:sale_trans_detail.*.type,mobil',
                    Rule::when(
                        $request['sale_trans_detail.*.type'] === 'mobil',
                        ['exists:App\Models\Mobil,_id']
                    )
                ]
            ],[
                'sale_trans_date.required' => 'Sales date (sales_trans_date) is required.',
                'sale_trans_date.date_format' => 'Sales date (sales_trans_date) must using ISO 8601.',
                'id_kendaraan.required' => 'ID kendaraan is required.',
                'id_kendaraan.exists' => 'ID Kendaraan not found.' ,
                'sale_trans_detail.*.type.required' => 'Kendaraan type is required',
                'sale_trans_detail.*.type.in' => 'Kendaraan type value must be "motor" or "mobil"',
                'sale_trans_detail.*.qty.required' => 'Quantity (qty) is required.',
                'sale_trans_detail.*.qty.numeric' => 'Quantity (qty) must be numeric',
                'sale_trans_detail.*.qty.gt' => 'Quantity (qty) must be numeric and greater than 0',
                'sale_trans_detail.*.motor_id.required_if' => 'motor_id is required.',
                'sale_trans_detail.*.motor_id.exists' => 'motor_id not found.',
                'sale_trans_detail.*.mobil_id.required_if' => 'mobil_id is required.',
                'sale_trans_detail.*.mobil_id.exists' => 'mobil_id not found.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            // Get 'harga' from collection kendaraan
            $harga = $this->kendaraanModel->findKendaraan($validator->validated()['id_kendaraan'], ['harga'])->first();

            // Set input data
            $inputData = [
                'sales_date' => $validator->validated()['sale_trans_date'],
                'sales_qty_kendaraan' => 0,
                'sales_total' => 0,
                'kendaraan_id' => $validator->validated()['id_kendaraan'],
                'sales_detail' => [],
                'created_at' => Carbon::now()->timezone('UTC')->toIso8601String()
            ];

            // Array of motor and mobil IDs
            $motors = [];
            $mobils = [];

            foreach ($validator->validated()['sale_trans_detail'] as $value) {
                $detail = [
                    'type' => $value['type'],
                    'qty' => $value['qty'],
                    'harga' => floatval($harga['harga']),
                    'subtotal' => intval($value['qty']) * floatval($harga['harga'])
                ];

                if ($value['type'] === 'motor') {
                    $checkStokMotor = $this->motorModel->checkStock($value['motor_id'], intval($value['qty']));
                    if ($checkStokMotor->isEmpty()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'The given data was invalid',
                            'errors' => [
                                'sale_trans_detail' => ['Stok motor dengan id '.$value['motor_id'].' tidak mencukupi.']
                            ]
                        ], 400);
                    }

                    $detail['motor_id'] = $value['motor_id'];

                    // push to $motors for update stock
                    array_push($motors, ['id' => $value['motor_id'], 'qty' => intval($value['qty'])]);
                }

                if ($value['type'] === 'mobil') {
                    $checkStokMobil = $this->mobilModel->checkStock($value['mobil_id'], intval($value['qty']));
                    if ($checkStokMobil->isEmpty()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'The given data was invalid',
                            'errors' => [
                                'sale_trans_detail' => ['Stok motor dengan id '.$value['motor_id'].' tidak mencukupi.']
                            ]
                        ], 400);
                    }

                    $detail['mobil_id'] = $value['mobil_id'];

                    // push to $mobils for update stock
                    array_push($mobils, ['id' => $value['mobil_id'], 'qty' => intval($value['qty'])]);
                }

                // Add to inputData
                array_push($inputData['sales_detail'], $detail);

                // Increate sales_qty_kendaraan & sales_total
                $inputData['sales_qty_kendaraan'] = intval($inputData['sales_qty_kendaraan']) + intval($value['qty']);
                $inputData['sales_total'] = floatval($inputData['sales_total']) + floatval($detail['subtotal']);
            }

            // Insert to database
            $input = $this->salesModel->insertSales($inputData);
            if ($input) {
                if ($motors && count($motors) > 0) {
                    $this->motorModel->decreseStock(1, $motors);
                }

                if ($mobils && count($mobils) > 0) {
                    $this->mobilModel->decreseStock(1, $mobils);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Succesfully added new data.'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add new data.'
                ], 500);
            }
        }
    }

    public function show(String $sales){
        if ($sales) {
            $data = $this->salesModel->getSales(['id' => $sales])[0];

            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found.',
                    'data' => $data ? SalesResource::make($data) : null
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Data not found.',
                    'data' => []
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide parameter "id".'
            ], 400);
        }
    }

    public function showSalesKendaraan (String|Int $kendaraan) {
        if ($kendaraan) {
            // Check if kendaraan exists
            $check = $this->kendaraanModel->findKendaraan($kendaraan, ['_id']);

            if ($check) {
                $data = $this->salesModel->getSales(['kendaraan' => $kendaraan]);

                return response()->json([
                    'success' => true,
                    'message' => ($data && count($data) > 0 ? 'Data found.' : 'No data available.'),
                    'count_data' => $data ? count($data) : 0,
                    'count_all' => $data ? count($data) : 0,
                    'data' => $data ? SalesResource::collection($data) : null
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
                'message' => 'Please provide parameter "kendaraan id".'
            ], 400);
        }
    }
}
