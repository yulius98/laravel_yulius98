<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasienResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_pasien' => $this->nama_pasien,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'id_rumah_sakit' => $this->id_rumah_sakit,
            'rumah_sakit' => $this->whenLoaded('rumah_sakit', function () {
                return [
                    'id' => $this->rumah_sakit->id,
                    'nama_rumah_sakit' => $this->rumah_sakit->nama_rumah_sakit,
                    'alamat' => $this->rumah_sakit->alamat,
                    'email' => $this->rumah_sakit->email,
                    'telepon' => $this->rumah_sakit->telepon,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
