<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    public function index(Request $request)
    {
        $loketId = $request->input('loket_id'); // Mendapatkan ID loket dari parameter request
        $status = $request->input('status'); // Mendapatkan status antrian dari parameter request

        $query = Antrian::query();

        if ($loketId) {
            $query->where('loket_id', $loketId); // Menambahkan kondisi filter jika ID loket diberikan
        }

        if ($status) {
            $query->where('status', $status); // Menambahkan kondisi filter jika status antrian diberikan
        }

        $antrian = $query->with('loket')->get();
        $data = $antrian->map(function ($item) {

            return [
                'id' => $item->id,
                'loket_id' => $item->loket_id,
                'nomor_antrian' => $item->nomor_antrian,
                'loket_nama' => $item->loket->nama,
                'status' => $item->status,
                'created_at' => \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                'updated_at' => \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i:s'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *

    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    public function store(Request $request)
    {
        // Menentukan loket secara otomatis
        $loketIndex = 1; // Menggunakan loket 1 sebagai loket default

        $loket = Loket::find($loketIndex); // Mendapatkan loket berdasarkan indeks loket yang dipilih

        // Jika loket yang ditemukan adalah null, artinya loket dengan indeks tersebut telah dihapus.
        // Maka, cari loket tersedia dengan indeks terkecil yang belum digunakan
        if ($loket === null) {
            $loket = Loket::whereNotIn('id', Antrian::pluck('loket_id'))->orderBy('id')->first();
            if ($loket === null) {
                return response()->json([
                    'message' => 'Tidak ada loket tersedia',
                ], 400);
            }
            $loketIndex = $loket->id; // Perbarui nilai loketIndex dengan loket yang ditemukan
        }

        // Menentukan nomor antrian berdasarkan loket
        $lastAntrianForLoket = Antrian::where('loket_id', $loket->id)->orderBy('id', 'desc')->first(); // Mendapatkan antrian terakhir berdasarkan loket

        // Mengecek apakah ada antrian sebelumnya
        if ($lastAntrianForLoket) {
            // Mendapatkan tanggal antrian terakhir
            $lastAntrianDate = $lastAntrianForLoket->created_at->format('Y-m-d');

            // Mendapatkan tanggal hari ini
            $todayDate = now()->format('Y-m-d');

            // Mengecek apakah tanggal telah berubah sejak antrian terakhir
            if ($todayDate != $lastAntrianDate) {
                // Jika tanggal berubah, atur nomor antrian kembali ke satu
                $nomorAntrian = 1;
            } else {
                // Jika tanggal tidak berubah, tambahkan 1 ke nomor antrian terakhir
                $nomorAntrian = $lastAntrianForLoket->nomor_antrian + 1;
            }
        } else {
            // Jika tidak ada antrian sebelumnya, atur nomor antrian ke satu
            $nomorAntrian = 1;
        }

        // Simpan data antrian ke dalam database dengan loket yang ditentukan
        $antrian = Antrian::create([
            'id' => $request->input('id'),
            'loket_id' => $loket->id,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'Belum Dipanggil',
        ]);

        return response()->json([
            'message' => 'Antrian berhasil ditambahkan',
            'data' => $antrian,
        ], 201);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show($id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $antrian,
        ]);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);

        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian tidak ditemukan.',
            ], 404);
        }

        $status = $request->input('status'); // Mendapatkan status antrian dari parameter request
        $loketId = $request->input('loket_id'); // Mendapatkan loket_id dari parameter request

        // Memvalidasi status antrian yang diberikan
        if (!in_array($status, ['Belum Dipanggil', 'Dipanggil', 'Dilayani', 'Selesai Dilayani', 'Tidak Datang'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Status antrian tidak valid.',
            ], 400);
        }

        // Memvalidasi perubahan status
        if ($status === 'Belum Dipanggil') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat mengubah status menjadi "Belum Dipanggil".',
            ], 400);
        } elseif ($status === 'Dipanggil' && $antrian->status === 'Dilayani') {
            return response()->json([
                'status' => 'error',

            ], 400);
        } elseif ($status === 'Dilayani' && $antrian->status === 'Selesai Dilayani') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat mengubah status menjadi "Dilayani" dari "Selesai Dilayani".',
            ], 400);
        } elseif ($status === 'Tidak Hadir') {
            $antrian->waktu_tidak_hadir = now();
        }

        // Memperbarui status dan loket_id
        $antrian->status = $status;
        $antrian->loket_id = $loketId;
        $antrian->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status antrian berhasil diperbarui.',
            'data' => Antrian::find($id),
        ]);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        //
    }
}
