<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Sales;

class SalesDetail extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'sales';
    protected $primaryKey = '_id';
    protected $fillable = ['type', 'qty', 'harga', 'subtotal', 'motor_id', 'mobil_id'];

    public function sales () {
        return $this->belongsTo(Sales::class);
    }

    public function motor () {
        return $this->hasOne('App\Models\Motor', 'motor_id', '_id');
    }

    public function mobil () {
        return $this->hasOne('App\Models\Mobil', 'mobil_id', '_id');
    }
}
