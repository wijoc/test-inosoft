<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $collection = 'mobil';
    protected $primaryKey = '_id';
    protected $fillable = ['mesin', 'kapasitas_penumpang', 'tipe', 'kendaraan_id', 'created_at', 'updated_at'];

    public function kendaraan () {
        return $this->belongsTo('App\Models\Kendaraan', 'kendaraan_id', '_id');
    }

    public function getMobils () {
        return Mobil::raw(function ($query) {
            return $query->aggregate([
                [
                    '$addFields' => [
                        'mobil_id' => [ '$toObjectId' => '$kendaraan_id' ]
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'kendaraan',
                        'localField' => 'mobil_id',
                        'foreignField' => '_id',
                        'pipeline' => [
                            // $project -> to select only fields we need. 0 -> exclude, 1-> include
                            [ '$project' => ['created_at' => 0, 'updated_at' => 0] ]
                        ],
                        'as' => 'kendaraan',
                    ],
                ]
            ]);
        });
    }

    public function insertMobil (Array $data, String $expect = 'bool') {
        if ($expect === 'id') {
            return Mobil::insertGetId($data);
        } else {
            return Mobil::insert($data);
        }
    }

    public function findMobil (String|Int $id, Int $withKendaraan = 0, Array $expect = ['_id', 'mesin', 'kapasitas_penumpang', 'tipe', 'kendaraan_id', 'created_at', 'updated_at']) {
        return Mobil::when($expect ?? false, function ($query) use ($expect) {
                    $query->select($expect);
                })
                ->when($withKendaraan ?? false, function ($query) {
                    $query->with(['kendaraan' => function ($query) {
                        $query->select('_id', 'tahun_keluaran', 'warna', 'harga');
                    }]);
                })
                ->find($id);
    }

    public function updateMobil (Array $data, String|Int $id) {
        return Mobil::where('_id', $id)->update($data);
    }

    public function deleteMobil (String|Int $id) {
        return Mobil::where('_id', $id)->delete();
    }

    public function decreseStock (Int $multiple = 0, Array $data) {
        if ($multiple > 0) {
            foreach ($data as $value) {
                Mobil::where('_id', $value['id'])->decrement('stok', $value['qty']);
            }
        } else {
            Mobil::where('_id', $data['id'])->decrement('stok', $data['qty']);
        }
    }
}
