<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokets = Loket::all();


        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $lokets,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataLokets = new Loket();

        $rules = [
            'nama' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Ditambahkan',
                'data' => $validator->errors(),
            ]);
        }

        $dataLokets->nama = $request->nama;

        $post = $dataLokets->save();

        return response()->json([
            'status' => true,
            'message' => "Data Berhasil Ditambah",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lokets = Loket::find($id);
        if ($lokets) {
            return response()->json([
                'status' => true,
                'message' => 'Data Ditemukan',
                'data' => $lokets,
            ]);
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
        $dataLokets = Loket::find($id);
        if (empty($dataLokets)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }

        $rules = [
            'nama' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Diupdate',
                'data' => $validator->errors(),
            ]);
        }

        $dataLokets->nama = $request->nama;

        $post = $dataLokets->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Mengupdate Data',
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
        $dataLokets = Loket::find($id);
        if (empty($dataLokets)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }

        $post = $dataLokets->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
