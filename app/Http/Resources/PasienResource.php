<?php

namespace App\Http\Resources;

use DateTime;
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
        // Hitung umur
        $tanggalLahir = new DateTime($this->tgl_lahir);

        $tanggalSekarang = new DateTime();
        $selisih = $tanggalLahir->diff($tanggalSekarang);
        $umur = $selisih->y;

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tgl_lahir' => $this->tgl_lahir,
            'umur' => $umur,
        ];
    }
}
