<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Rujuk;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PasienResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\RiwayatPasienResource;

class PemeriksaanController extends Controller
{
    /**
     * @OA\Post(
     *     path="/store-pasien",
     *     tags={"Projects"},
     *     summary="Store Pasien API",
     *     description="Return Pasien Data",
     *     operationId="store-pasien",
     *     @OA\Parameter(
     *          name="nama",
     *          description="Nama Pasien",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="jenis_kelamin",
     *          description="Jenis Kelamin",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="tgl_lahir",
     *          description="Tanggal Lahir",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="alamat",
     *          description="Alamat",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "meta": {
     *                     "code":200,
     *                     "status": "success",
     *                     "message": "Pasien Berhasil ditambahkan!"
     *                },
     *               "data": {
     *                      "id":1,
     *                      "name": "Andi Suartika",
     *                      "email": "andisuartika@gmail.com",
     *                      "role": "dokter",
     *                }
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function storePasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $pasien = Pasien::create([
                ...$request->validate([
                    'nama' => 'required|string|max:20',
                    'jenis_kelamin' => 'required',
                    'tgl_lahir' => 'required',
                    'alamat' => 'required',
                ]),
            ]);
            DB::commit();
            return ResponseFormatter::success([
                new PasienResource($pasien),
            ], 'Pasien Berhasil ditambahkan!');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'System Error', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/store-periksa",
     *     tags={"Projects"},
     *     summary="Store Hasil Pemeriksaan API",
     *     description="Return Periksa Data",
     *     operationId="store-periksa",
     *     @OA\Parameter(
     *          name="pasien_id",
     *          description="Id Pasien",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="int"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="keluhan",
     *          description="Keluhan",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="tekanan_darah",
     *          description="Tekanan Darah",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="nadi",
     *          description="Nadi",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="rr",
     *          description="Respitory Rate",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="suhu",
     *          description="Suhu",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="fisik",
     *          description="Hasil Pemeriksaan Fisik",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="diagnosis",
     *          description="Diagnosis",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="tata_laksana",
     *          description="Tata Laksana",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="rujuk",
     *          description="Rujuk",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="bool"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="tempat",
     *          description="Tempat Rujuk",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="keterangan",
     *          description="Keterangan Rujuk",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="tarif",
     *          description="Tarif",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="int"
     *          )
     *     ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "meta": {
     *                     "code":200,
     *                     "status": "success",
     *                     "message": "Hasil Pemeriksaaan Berhasil ditambahkan!"
     *                },
     *               "data": {
     *                         "dokter_id": 1,
     *                         "tanggal": "2023-11-08T11:13:56.803217Z",
     *                         "pasien_id": 2,
     *                         "keluhan": "Flu",
     *                         "tekanan_darah": "120/80 mmHg",
     *                         "nadi": "75/menit",
     *                         "rr": "13/menit",
     *                         "suhu": "35",
     *                         "fisik": "Tingi Badan 150cm. Berat Badan 50Kg",
     *                         "diagnosis": "Flu Ringan",
     *                         "tata_laksana": "Obat Flu",
     *                         "rujuk": 1,
     *                         "tarif": 100000,
     *                         "updated_at": "2023-11-08T11:13:56.000000Z",
     *                         "created_at": "2023-11-08T11:13:56.000000Z",
     *                         "id": 29
     *                }
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function storePeriksa(Request $request)
    {
        // Validasi
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
        DB::beginTransaction();
        try {

            // Create Hasil Pemeriksaan
            $periksa = Pemeriksaan::create([
                'dokter_id' => Auth::user()->id,
                'tanggal' => now(),
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

            // Jika dirujuk, Create Rujuk.
            if ($periksa->rujuk === 1) {
                $rujuk = Rujuk::create([
                    'periksa_id' => $periksa->id,
                    'pasien_id' => $request->pasien_id,
                    'tanggal' => now(),
                    'tempat' => $request->tempat,
                    'keterangan' => $request->keterangan,
                ]);
            }

            DB::commit();
            return ResponseFormatter::success([
                $periksa
            ], 'Pasien Berhasil ditambahkan!');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'System Error', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/get-pasien?nama={nama-pasien}",
     *     tags={"Projects"},
     *     summary="Get Data Pasien API",
     *     description="Return Pasien Data",
     *     operationId="get-pasien",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "meta": {
     *                     "code":200,
     *                     "status": "success",
     *                     "message": "Data Pasien Berhasil diambil!"
     *                },
     *               "data": {
     *                         {
     *                              "id":1,
     *                              "name": "Andi Suartika",
     *                              "email": "andisuartika@gmail.com",
     *                              "tgl_lahir": "2000-04-17",
     *                              "alamat": "Sangket",
     *                         },
     *                         {
     *                              "id":2,
     *                              "name": "Made Sedana",
     *                              "email": "madesedaba@gmail.com",
     *                              "tgl_lahir": "1988-02-12",
     *                              "alamat": "Singaraja",
     *                         },
     *                }
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

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

    /**
     * @OA\Get(
     *     path="/get-history",
     *     tags={"Projects"},
     *     summary="Get Data History API",
     *     description="Return History Data",
     *     operationId="get-history",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "meta": {
     *                     "code":200,
     *                     "status": "success",
     *                     "message": "Riwayat Pemeriksaan berhasil diambil"
     *                },
     *               "data": {
     *                         {
     *                              "tanggal": "2023-10-23",
     *                              "nama": "Boy",
     *                              "jenis_kelamin": "Laki-laki",
     *                              "umur": 40,
     *                              "tgl_lahir": "1983-10-15",
     *                              "alamat": "singaraja",
     *                              "keluhan": "Flu",
     *                              "tekanan_darah": "110/120",
     *                              "nadi": "45",
     *                              "rr": "14",
     *                              "suhu": "37",
     *                              "fisik": "Flu disertai dengan batuk berdahak",
     *                              "diagnosis": "flu dan batuk",
     *                              "tata_laksana": "obat flu batuk 2x1",
     *                              "tarif": "100000.00",
     *                              "rujuk": 0
     *                         },
     *                         {
     *                              "tanggal": "2023-10-23",
     *                              "nama": "Boy",
     *                              "jenis_kelamin": "Laki-laki",
     *                              "umur": 40,
     *                              "tgl_lahir": "1983-10-15",
     *                              "alamat": "singaraja",
     *                              "keluhan": "Flu",
     *                              "tekanan_darah": "110/120",
     *                              "nadi": "45",
     *                              "rr": "14",
     *                              "suhu": "37",
     *                              "fisik": "Flu disertai dengan batuk berdahak",
     *                              "diagnosis": "flu dan batuk",
     *                              "tata_laksana": "obat flu batuk 2x1",
     *                              "tarif": "100000.00",
     *                              "rujuk": 0
     *                         },
     *                }
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

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
