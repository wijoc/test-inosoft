<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->_id ?? null,
            'sales_date' => $this->sales_date ? Carbon::parse($this->sales_date)->setTimezone('UTC')->format('c') : null,
            'sales_qty_kendaraan' => $this->sales_qty_kendaraan ?? null,
            'sales_total' => $this->sales_total ?? null,
            'kendaraan' => [],
            'sales_detail' => $this->sales_detail ?? []
        ];

        if ($this->kendaraan) {
            $array['kendaraan'] = [
                'id' => (string)$this->kendaraan['_id'] ?? null,
                'tahun_keluaran' => $this->kendaraan['tahun_keluaran'] ?? null,
                'warna' => $this->kendaraan['warna'] ?? null,
                'harga' => $this->kendaraan['harga'] ?? null
            ];
        }

        return $array;
    }
}
