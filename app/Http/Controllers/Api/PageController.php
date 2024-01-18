<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Page::orderBy('nama', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'page' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePageRequest $request)
    {
        $dataPage = new Page;

        $dataPage->nama = $request->nama;
        $dataPage->alamat = $request->alamat;
        $dataPage->instagram = $request->instagram;
        $dataPage->website = $request->website;
        $dataPage->logo = $request->logo;

        // Proses unggah gambar dan simpan nama file ke basis data
        if ($request->logo) {

            $extension = $request->logo->extension();
            $fileName = Str::random(10) . '.';
            $path = $request->logo->storeAs('logo', $fileName . '.' . $extension);

            $dataPage->logo = $path;
        }

        $dataPage->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses memasukkan data',
        ]);
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Page::find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data,
            ],);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataPage = Page::find($id);
        if (empty($dataPage)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }

        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'instagram' => 'required',
            'website' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Ditambahkan',
                'data' => $validator->errors(),
            ]);
        }

        $dataPage->nama = $request->nama;
        $dataPage->alamat = $request->alamat;
        $dataPage->instagram = $request->instagram;
        $dataPage->website = $request->website;
        $dataPage->logo = $request->logo;

        // Periksa apakah ada file logo yang diunggah
        if ($request->hasFile('logo')) {
            // Hapus logo lama dari penyimpanan jika ada
            if ($dataPage->logo) {
                Storage::delete($dataPage->logo);
            }

            // Proses unggah logo baru dan simpan nama file ke basis data
            $fileName = Str::random(20);
            $extension = $request->logo->getClientOriginalExtension();
            $path = $request->logo->storeAs('logo', $fileName . '.' . $extension);
            $dataPage->logo = $path;
        }

        $dataPage->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses mengupdate data',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataPage = Page::find($id);
        if (empty($dataPage)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }

        $post = $dataPage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
