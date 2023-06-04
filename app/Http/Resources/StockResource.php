<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StockResource extends JsonResource
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
            'tahun_keluaran' => $this->tahun_keluaran ?? null,
            'warna' => $this->warna ?? null,
            'harga' => $this->harga ?? null,
            'created_at' => Carbon::parse($this->created_at)->setTimezone('UTC')->format('c') ?? null,
            'total_stok' => $this->total_stok ?? null,
            'motor' => [],
            'mobil' => [],
        ];

        if (isset($this->motor)) {
            for ($mi = 0; $mi < count($this->motor); $mi++) {
                $array['motor'][$mi]['id'] = (string)$this->motor[$mi]->_id;
                $array['motor'][$mi]['stok_motor'] = $this->motor[$mi]['stok'];
            }
        }

        if (isset($this->mobil)) {
            for ($mi = 0; $mi < count($this->mobil); $mi++) {
                $array['mobil'][$mi]['id'] = (string)$this->mobil[$mi]->_id;
                $array['mobil'][$mi]['stok_mobil'] = $this->mobil[$mi]['stok'];
            }
        }

        return $array;
    }
}
