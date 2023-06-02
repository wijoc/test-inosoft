<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $collection = 'kendaraan';
    protected $primaryKey = '_id';
    protected $fillable = ['tahun_keluaran', 'warna', 'harga', 'created_at', 'updated_at'];
}
