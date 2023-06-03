<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MotorResource extends JsonResource
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
            'id' => $this->_id,
            'mesin' => $this->mesin.'cc',
            'tipe_suspensi' => $this->tipe_suspensi === 'usd' ? 'Up-side Down' : 'Telescopic',
            'tipe_transmisi' => ucfirst($this->tipe_transmisi),
            'kendaraan' => [],
            'created_at' => Carbon::parse($this->created_at)->setTimezone('UTC')->format('c'),
            'last_updated' => $this->updated_at ? Carbon::parse($this->updated_at)->setTimezone('UTC')->format('c') : null
        ];

        if ($this->kendaraan) {
            $array['kendaraan'] = [
                'id' => $this->kendaraan['_id']['$oid'] ?? $this->kendaraan['_id'],
                'tahun_keluaran' => $this->kendaraan['tahun_keluaran'],
                'warna' => $this->kendaraan['warna'],
                'harga' => $this->kendaraan['harga']
            ];
        }

        return $array;
    }
}
