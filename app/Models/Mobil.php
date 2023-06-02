<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $collection = 'mobile';
    protected $primaryKey = '_id';
    protected $fillable = ['mesin', 'kapasitas_penumpang', 'tipe', 'kendaraan_id', 'created_at', 'updated_at'];
}
