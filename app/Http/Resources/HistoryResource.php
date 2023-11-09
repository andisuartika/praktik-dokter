<?php

namespace App\Http\Resources;

use DateTime;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        $pasien = Pasien::find($this->pasien_id);

        // Hitung umur
        $tanggalLahir = new DateTime($pasien->tgl_lahir);

        $tanggalSekarang = new DateTime();
        $selisih = $tanggalLahir->diff($tanggalSekarang);
        $umur = $selisih->y;
        return [
            "tanggal" => $this->tanggal,
            "nama" => $pasien->nama,
            "umur" => $umur,
            "jenis_kelamin" => $pasien->jenis_kelamin,
            "tgl_lahir" => $pasien->tgl_lahir,
            "alamat" => $pasien->alamat,
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
