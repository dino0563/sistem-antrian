<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role', 'pegawai')->paginate(5);
        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $users
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
        $datausers = new User;

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Ditambahkan',
                'data' => $validator->errors()
            ]);
        }

        $datausers->name = $request->name;
        $datausers->email = $request->email;
        $datausers->password = bcrypt($request->password);
        $datausers->role = $request->role;

        $post = $datausers->save();

        return response()->json([
            'status' => true,
            'message' => "Data Berhasil Ditambah"
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
        $users = User::find($id);
        if ($users) {
            return response()->json([
                'status' => true,
                'message' => 'Data Ditemukan',
                'data' => $users
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
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
        $datausers = User::find($id);
        if (empty($datausers)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Diupdate',
                'data' => $validator->errors()
            ]);
        }

        $datausers->name = $request->name;
        $datausers->email = $request->email;
        $datausers->password = bcrypt($request->password);
        $datausers->role = $request->role;

        $post = $datausers->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Mengupdate Data'
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
        $datausers = User::find($id);
        if (empty($datausers)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $post = $datausers->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function search($name)
    {
        return User::where("name", $name);
    }
}
