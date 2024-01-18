<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LoketsController extends Controller
{

    const API_URL = "http://127.0.0.1:8001/api/lokets";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $client = new Client();
        $url = static::API_URL;
        if ($request->input('page') != '') {
            $url .= "?page=" . $request->input('page');
        }
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $lokets = $contentArray['data'];
        return view('admin/loket', ['data' => $lokets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nama = $request->nama;

        $parameter = [
            'nama' => $nama,
        ];

        $client = new Client();
        $url = "http://127.0.0.1:8001/api/lokets";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter),
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/loket')->withErrors($error);
        } else {
            return redirect()->to('admin/loket')->with('success', 'Berhasil Memasukkan Data');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = new Client();
    $url = static::API_URL . "/$id";
    $response = $client->request('GET', $url);
    $content = $response->getBody()->getContents();
    $contentArray = json_decode($content, true);

    if ($contentArray['status'] != true) {
        $error = $contentArray['message'];
        return redirect()->to('pegawai/panggil')->withErrors($error);
    } else {
        $lokets = $contentArray['data']; // Ganti variabel $data menjadi $lokets
        return view('pegawai/panggil', ['data' => $lokets]);
    }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = new Client();
        $url = "http://127.0.0.1:8001/api/lokets/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('admin/loket')->withErrors($error);
        } else {
            $lokets = $contentArray['data'];
            return view('admin/loket', ['data' => $lokets]);
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
        $nama = $request->nam;

        $parameter = [
            'nama' => $nama,
        ];

        $client = new Client();
        $url = "http://127.0.0.1:8001/api/lokets/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter),
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/loket')->withErrors($error);
        } else {
            return redirect()->to('admin/loket')->with('success', 'Berhasil Update Data');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = new Client();
        $url = "http://127.0.0.1:8001/api/lokets/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/loket')->withErrors($error);
        } else {
            return redirect()->to('admin/loket')->with('success', 'Berhasil Delete Data');
        }
    }
}
