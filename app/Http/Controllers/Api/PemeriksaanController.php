<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\PasienResource;
use App\Http\Resources\RiwayatPasienResource;
use App\Models\Rujuk;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
{
    public function storePasien(Request $request)
    {
        try {
            $pasien = Pasien::create([
                ...$request->validate([
                    'nama' => 'required|string|max:20',
                    'jenis_kelamin' => 'required',
                    'tgl_lahir' => 'required',
                    'alamat' => 'required',
                ]),
            ]);

            return ResponseFormatter::success([
                new PasienResource($pasien),
            ], 'Pasien Berhasil ditambahkan!');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'System Error', 500);
        }
    }

    public function storePeriksa(Request $request)
    {
        try {
            $request->validate([
                'pasien_id' => 'required',
                'keluhan' => 'required',
                'tekanan_darah' => 'required',
                'nadi' => 'required',
                'rr' => 'required',
                'suhu' => 'required',
                'fisik' => 'required',
                'diagnosis' => 'required',
                'tata_laksana' => 'required',
                'rujuk' => 'required',
                'tarif' => 'required',
            ]);

            $periksa = Pemeriksaan::create([
                'dokter_id' => Auth::user()->id,
                'pasien_id' => $request->pasien_id,
                'keluhan' => $request->keluhan,
                'tekanan_darah' => $request->tekanan_darah,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'suhu' => $request->suhu,
                'fisik' => $request->fisik,
                'diagnosis' => $request->diagnosis,
                'tata_laksana' => $request->tata_laksana,
                'rujuk' => $request->rujuk,
                'tarif' => $request->tarif,
            ]);

            if ($periksa->rujuk === 1) {
                $rujuk = Rujuk::create([
                    'periksa_id' => $periksa->id,
                    'pasien_id' => $request->pasien_id,
                    'tanggal' => now(),
                    'tempat' => $request->tempat,
                    'keterangan' => $request->keterangan,
                ]);
            }

            return ResponseFormatter::success([
                $periksa
            ], 'Pasien Berhasil ditambahkan!');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'System Error', 500);
        }
    }

    public function getPasien(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('nama');

        if ($id) {
            $pasien = Pasien::find($id);

            if ($pasien) {
                return ResponseFormatter::success(
                    new PasienResource($pasien),
                    'Data Pasien berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Pasien tidak ada',
                    404
                );
            }
        }


        $users = Pasien::latest()->paginate(5);
        if ($name) {
            $users = Pasien::where('nama', 'like', '%' . $name . '%')->get();
        }

        if ($users) {
            return ResponseFormatter::success(
                PasienResource::collection($users),
                'Data Pasien berhasil diambil'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data Pasien tidak ada',
                404
            );
        }
    }

    public function riwayatPasien(Request $request)
    {
        $id = $request->input('id');
        $pasien_id = $request->input('pasien_id');


        if ($id) {
            $riwayat = Pemeriksaan::find($id);

            if ($riwayat) {
                return ResponseFormatter::success(
                    new RiwayatPasienResource($riwayat),
                    'Riwayat Pasien berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Riwayat Pasien tidak ada',
                    404
                );
            }
        }


        $riwayat = Pemeriksaan::where('pasien_id', '=', $pasien_id)->orderBy('created_at', 'desc')->get();

        if ($riwayat) {
            return ResponseFormatter::success(
                RiwayatPasienResource::collection($riwayat),
                'Riwayat Pasien berhasil diambil'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Riwayat Pasien tidak ada',
                404
            );
        }
    }

    public function history(Request $request)
    {
        $id = $request->input('id');
        $today = $request->input('today');


        if ($id) {
            $riwayat = Pemeriksaan::find($id);

            if ($riwayat) {
                return ResponseFormatter::success(
                    new HistoryResource($riwayat),
                    'Riwayat Pemeriksaan berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Riwayat Pemeriksaan tidak ada',
                    404
                );
            }
        }


        $riwayat = Pemeriksaan::orderBy('created_at', 'desc')->get();
        if ($today) {
            $riwayat = Pemeriksaan::where('tanggal', '=', now()->toDateString())->orderBy('created_at', 'desc')->get();
        }

        if ($riwayat) {
            return ResponseFormatter::success(
                HistoryResource::collection($riwayat),
                'Riwayat Pemeriksaan berhasil diambil'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Riwayat Pemeriksaan tidak ada',
                404
            );
        }
    }
}
