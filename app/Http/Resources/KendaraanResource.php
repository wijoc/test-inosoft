<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class KendaraanResource extends JsonResource
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
        ];

        return $array;
    }
}
