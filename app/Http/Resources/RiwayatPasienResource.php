<?php

namespace App\Http\Resources;

use App\Models\Rujuk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatPasienResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->rujuk === 1) {
            $rujuk = Rujuk::where('periksa_id', $this->id)->first();
            return [
                "tanggal" => $this->tanggal,
                "keluhan" => $this->keluhan,
                "tekanan_darah" => $this->tekanan_darah,
                "nadi" => $this->nadi,
                "rr" => $this->rr,
                "suhu" => $this->suhu,
                "fisik" => $this->fisik,
                "diagnosis" => $this->diagnosis,
                "tata_laksana" => $this->tata_laksana,
                "tarif" => $this->tarif,
                "rujuk" => $this->rujuk,
                "tempat" => $rujuk->tempat,
                "keterangan" => $rujuk->keterangan,
            ];
        } else {

            return [
                "tanggal" => $this->tanggal,
                "keluhan" => $this->keluhan,
                "tekanan_darah" => $this->tekanan_darah,
                "nadi" => $this->nadi,
                "rr" => $this->rr,
                "suhu" => $this->suhu,
                "fisik" => $this->fisik,
                "diagnosis" => $this->diagnosis,
                "tata_laksana" => $this->tata_laksana,
                "tarif" => $this->tarif,
                "rujuk" => $this->rujuk,
            ];
        }
    }
}
