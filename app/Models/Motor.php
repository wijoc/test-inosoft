<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;

class Motor extends Model
{
    use HasFactory;

    protected $collection = 'motor';
    protected $primaryKey = '_id';
    protected $fillable = ['mesin', 'tipe_suspensi', 'tipe_transmisi', 'kendaraan_id', 'created_at', 'updated_at'];

    public function kendaraan () {
        return $this->belongsTo('App\Models\Kendaraan', 'kendaraan_id', '_id');
    }

    public function getMotors () {
        return Motor::raw(function ($query) {
            return $query->aggregate([
                [
                    '$addFields' => [
                        'motor_id' => [ '$toObjectId' => '$kendaraan_id' ]
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'kendaraan',
                        'localField' => 'motor_id',
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

    public function insertMotor (Array $data, String $expect = 'bool') {
        if ($expect === 'id') {
            return Motor::insertGetId($data);
        } else {
            return Motor::insert($data);
        }
    }

    public function findMotor (String|Int $id, Int $withKendaraan = 0, Array $expect = ['_id', 'mesin', 'tipe_suspensi', 'tipe_transmisi', 'kendaraan_id', 'created_at', 'updated_at']) {
        return Motor::when($expect ?? false, function ($query) use ($expect) {
                    $query->select($expect);
                })
                ->when($withKendaraan > 0 ?? false, function ($query) {
                    $query->with(['kendaraan' => function ($query) {
                        $query->select('_id', 'tahun_keluaran', 'warna', 'harga');
                    }]);
                })
                ->find($id);
    }

    public function updateMotor (Array $data, String|Int $id) {
        return Motor::where('_id', $id)->update($data);
    }

    public function deleteMotor (String|Int $id) {
        return Motor::where('_id', $id)->delete();
    }

    public function decreaseStock (Int $multiple = 0, Array $data) {
        if ($multiple > 0) {
            foreach ($data as $value) {
                return Motor::where('_id', $value['id'])->decrement('stok', $value['qty']);
            }
        } else {
            return Motor::where('_id', $data['id'])->decrement('stok', $data['qty']);
        }
    }

    public function increaseStock (Int $multiple = 0, Array $data) {
        if ($multiple > 0) {
            foreach ($data as $value) {
                return Motor::where('_id', $value['id'])->increment('stok', $value['qty']);
            }
        } else {
            return Motor::where('_id', $data['id'])->increment('stok', $data['qty']);
        }
    }

    public function checkStock (String $id, Int $qty) {
        return Motor::where('_id', $id)->where('stok', '>=', $qty)->get();
    }

    public function checkKendaraanId (String $motor, String $kendaraan) {
        return Motor::where('_id', $motor)->where('kendaraan_id', $kendaraan)->get();
    }
}
