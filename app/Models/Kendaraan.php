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
        return $this->embedsMany(Motor::class);
    }

    public function mobils () {
        return $this->embedsMany(Mobils::class);
    }

    public function insertKendaraan (Array $data, String $expect = 'bool') {
        if ($expect === 'id') {
            return Kendaraan::insert($data);
        } else {
            return Kendaraan::insertGetId($data);
        }
    }

    public function findKendaraan (String $id) {
        return Kendaraan::find($id);
    }

    public function checkKendaraan (String $id) {
        return Kendaraan::select('_id')->find($id);
    }

    public function updateKendaraan (Array $data, String|Int $id) {
        return Kendaraan::where('_id', $id)->update($data);
    }

    public function deleteKendaraan (String|Int $id) {
        return Kendaraan::where('_id', $id)->delete();
    }
}
