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

    public function index () {
        $data = [];

        return response()->json([
            'success' => true,
            'message' => ($data && count($data) > 0 ? 'Data found.' : 'No data available.'),
            'count_data' => $data ? count($data) : 0,
            'count_all' => $data ? count($data) : 0,
            'data' => $data ?? null
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
                'sale_trans_date.date_format' => 'Sales date (sales_trans_date) must using ISO 8601 or Unix time format .',
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
                'detail' => [],
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
                    $detail['motor_id'] = $value['motor_id'];

                    // push to $motors for update stock
                    array_push($motors, ['id' => $value['motor_id'], 'qty' => intval($value['qty'])]);
                }

                if ($value['type'] === 'mobil') {
                    $detail['mobil_id'] = $value['mobil_id'];

                    // push to $mobils for update stock
                    array_push($mobils, ['id' => $value['mobil_id'], 'qty' => intval($value['qty'])]);
                }

                // Add to inputData
                array_push($inputData['detail'], $detail);

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

    public function show(Sales $sales)
    {
        //
    }
}
