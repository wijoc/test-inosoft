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
                ->when($withKendaraan ?? false, function ($query) {
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
}
