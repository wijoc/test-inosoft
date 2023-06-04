<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Motor;
use App\Models\Mobil;

class Kendaraan extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'kendaraan';
    protected $primaryKey = '_id';
    protected $fillable = ['tahun_keluaran', 'warna', 'harga', 'created_at', 'updated_at'];

    public function motors () {
        return $this->hasMany('App\Models\Motor', '_id', 'kendaraan_id');
    }

    public function mobils () {
        return $this->hasMany(Mobils::class);
    }

    public function getKendaraans () {
        return Kendaraan::select('_id', 'tahun_keluaran', 'warna', 'harga', 'created_at')->get();
    }

    public function getAllStock () {
        return Kendaraan::raw(function ($query) {
            return $query->aggregate([
                [
                    '$addFields' => [
                        'str_id' => [ '$toString' => '$_id' ]
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'motor',
                        'localField' => 'str_id',
                        'foreignField' => 'kendaraan_id',
                        'pipeline' => [
                            // $project -> to select only fields we need. 0 -> exclude, 1-> include
                            [ '$project' => ['_id' => 1, 'stok' => 1] ]
                        ],
                        'as' => 'motor',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'mobil',
                        'localField' => 'str_id',
                        'foreignField' => 'kendaraan_id',
                        'pipeline' => [
                            // $project -> to select only fields we need. 0 -> exclude, 1-> include
                            [ '$project' => ['_id' => 1, 'stok' => 1] ]
                        ],
                        'as' => 'mobil',
                    ],
                ],
                [
                    '$addFields' => [
                        'total_stok' => [
                            '$sum' => [
                                [ '$sum' => '$motor.stok' ],
                                [ '$sum' => '$mobil.stok' ]
                            ]
                        ]
                    ]
                ]
            ]);
        });
    }

    public function getStock (String|Int $id) {
        return Kendaraan::raw(function ($query) use ($id) {
            return $query->aggregate([
                [
                    '$addFields' => [
                        'str_id' => [ '$toString' => '$_id' ]
                    ]
                ],
                [
                    '$match' => [
                        'str_id' => $id
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'motor',
                        'localField' => 'str_id',
                        'foreignField' => 'kendaraan_id',
                        'pipeline' => [
                            // $project -> to select only fields we need. 0 -> exclude, 1-> include
                            [ '$project' => ['_id' => 1, 'stok' => 1] ]
                        ],
                        'as' => 'motor',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'mobil',
                        'localField' => 'str_id',
                        'foreignField' => 'kendaraan_id',
                        'pipeline' => [
                            // $project -> to select only fields we need. 0 -> exclude, 1-> include
                            [ '$project' => ['_id' => 1, 'stok' => 1] ]
                        ],
                        'as' => 'mobil',
                    ],
                ],
                [
                    '$addFields' => [
                        'total_stok' => [
                            '$sum' => [
                                [ '$sum' => '$motor.stok' ],
                                [ '$sum' => '$mobil.stok' ]
                            ]
                        ]
                    ]
                ]
            ]);
        });
    }

    public function insertKendaraan (Array $data, String $expect = 'bool') {
        if ($expect === 'id') {
            return Kendaraan::insertGetId($data);
        } else {
            return Kendaraan::insert($data);
        }
    }

    public function findKendaraan (String $id, Array $expect = ['_id', 'tahun_keluaran', 'warna', 'harga', 'created_at', 'updated_at']) {
        return Kendaraan::select($expect)->find($id);
    }

    public function updateKendaraan (Array $data, String|Int $id) {
        return Kendaraan::where('_id', $id)->update($data);
    }

    public function deleteKendaraan (String|Int $id) {
        return Kendaraan::where('_id', $id)->delete();
    }
}
