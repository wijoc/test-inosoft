<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'sales';
    protected $primaryKey = '_id';
    protected $fillable = ['sales_date', 'sales_qty_kendaraan', 'sales_total', 'kendaraan_id', 'sales_detail', 'created_at', 'updated_at'];

    public function insertSales (Array $data) {
        return Sales::insert($data);
    }
}
