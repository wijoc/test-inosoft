<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\SalesDetail;

class Sales extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'sales';
    protected $primaryKey = '_id';
    protected $fillable = ['sales_date', 'sales_qty_kendaraan', 'sales_total', 'kendaraan_id', 'sales_detail', 'created_at', 'updated_at'];

    public function kendaraan () {
        return $this->belongsTo('App\Models\Kendaraan', 'kendaraan_id', '_id');
    }

    public function detail () {
        return $this->embedsMany(SalesDetail::class);
    }

    public function insertSales (Array $data) {
        return Sales::insert($data);
    }

    public function getSales (Array $filter) {
        return Sales::select('sales_date', 'sales_qty_kendaraan', 'sales_total', 'kendaraan_id', 'sales_detail')
                    ->when(isset($filter['date_begin']) && isset($filter['date_end']) ?? false, function ($query) use ($filter) {
                        $query->whereDate('sales_date', '>=', [$filter['date_begin'], $filter['date_end']]);
                    })
                    ->when($filter['id'] ?? false, function ($query, $id) {
                        $query->where('_id', $id);
                    })
                    ->when($filter['kendaraan'] ?? false, function ($query, $kendaraan) {
                        $query->where('kendaraan_id', $kendaraan);
                    })
                    ->raw(function ($query) {
                        return $query->aggregate([
                            [ '$unwind' => '$sales_detail' ], // Unwind the array field
                            [
                                '$addFields' => [
                                    'mtr_id' => [ '$toObjectId' => '$sales_detail.motor_id' ],
                                    'mbl_id' => [ '$toObjectId' => '$sales_detail.mobil_id' ],
                                    'k_id' => [ '$toObjectId' => '$kendaraan_id' ],
                                ]
                            ],
                            [
                                '$lookup' => [
                                    'from' => 'motor',
                                    'localField' => 'mtr_id',
                                    'foreignField' => '_id',
                                    'pipeline' => [
                                        // $project -> to select only fields we need. 0 -> exclude, 1-> include
                                        [ '$project' => ['stok' => 0, 'created_at' => 0, 'updated_at' => 0] ]
                                    ],
                                    'as' => 'sales_detail.motor',
                                ],
                            ],
                            [
                                '$lookup' => [
                                    'from' => 'mobil',
                                    'localField' => 'mbl_id',
                                    'foreignField' => '_id',
                                    'pipeline' => [
                                        // $project -> to select only fields we need. 0 -> exclude, 1-> include
                                        [ '$project' => ['stok' => 0, 'created_at' => 0, 'updated_at' => 0] ]
                                    ],
                                    'as' => 'sales_detail.mobil',
                                ],
                            ],
                            [
                                '$lookup' => [
                                    'from' => 'kendaraan',
                                    'localField' => 'k_id',
                                    'foreignField' => '_id',
                                    'pipeline' => [
                                        // $project -> to select only fields we need. 0 -> exclude, 1-> include
                                        [ '$project' => ['created_at' => 0, 'updated_at' => 0] ]
                                    ],
                                    'as' => 'kendaraan',
                                ],
                            ],
                            [
                                '$group' => [
                                    '_id' => '$_id',
                                    'sales_date' => [ '$first' => '$sales_date' ],
                                    'sales_qty_kendaraan' => [ '$first' => '$sales_qty_kendaraan' ],
                                    'sales_total' => [ '$first' => '$sales_total' ],
                                    'kendaraan_id' => [ '$first' => '$kendaraan_id' ],
                                    'kendaraan' => [ '$first' => '$kendaraan' ],
                                    'sales_detail' => [ '$push' => '$sales_detail' ]
                                ]
                            ]
                        ]);
                    });
    }
}
