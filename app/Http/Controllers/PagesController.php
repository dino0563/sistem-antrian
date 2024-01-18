<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    const API_URL = "http://127.0.0.1:8002/api/pages";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();
        $url = static::API_URL;
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['page'];

        return view('user/home', ['page' => $data]);
    }

    public function indexdisplay()
    {
        $client = new Client();
        $url = static::API_URL;
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['page'];

        return view('display', ['page' => $data]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'instagram' => 'required',
            'website' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->to('superadmin/user-interface/1')->withErrors($validator);
        }

        $parameter = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'instagram' => $request->instagram,
            'website' => $request->website,
        ];

        // Periksa apakah ada file logo yang diunggah
        if ($request->hasFile('logo')) {
            // Hapus logo lama dari penyimpanan jika ada
            $client = new Client();
            $oldDataUrl = self::API_URL . "/$id";
            $oldDataResponse = $client->request('GET', $oldDataUrl);
            $oldDataContent = $oldDataResponse->getBody()->getContents();
            $oldData = json_decode($oldDataContent, true);

            if ($oldData['status'] === true && $oldData['data']['logo']) {
                // Hapus gambar lama dari direktori "storage/logo"
                Storage::delete($oldData['data']['logo']);
            }

            // Proses unggah logo baru dan simpan nama file ke parameter
            $extension = $request->logo->extension();
            $fileName = Str::random(10) . '.';
            $path = $request->logo->storeAs('logo', $fileName . '.' . $extension);
            $parameter['logo'] = $path;
        }

        $client = new Client();
        $url = self::API_URL . "/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter),
        ]);

        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('superadmin/user-interface/1')->withErrors($error);
        } else {
            return redirect()->to('superadmin/user-interface/1')->with('success', 'Berhasil Update Data');
        }
    }

    public function edit($id)
    {
        $client = new Client();
        $url = "http://127.0.0.1:8002/api/pages/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('superadmin/user-interface')->withErrors($error);
        } else {
            $data = $contentArray['data'];
            return view('superadmin.user-interface', ['data' => $data]);
        }
    }
}
