<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    const API_URL = "http://127.0.0.1:8000/api/users";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current_url = url()->current();
        $client = new Client();
        $url = static::API_URL;
        if ($request->input('page') != '') {
            $url .= "?page=" . $request->input('page');
        }
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $users = $contentArray['data'];
        foreach ($users['links'] as $key => $value) {
            $users['links'][$key]['url2'] = str_replace(static::API_URL, $current_url, $value['url']);
        }

        return view('admin/pegawai', ['data' => $users]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $role = $request->role;
        $email = $request->email;
        $password = $request->password;

        $parameter = [
            'name' => $name,
            'role' => $role,
            'email' => $email,
            'password' => $password,
        ];

        $client = new Client();
        $url = "http://127.0.0.1:8000/api/users";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter),
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/pegawai')->withErrors($error);
        } else {
            return redirect()->to('admin/pegawai')->with('success', 'Berhasil Memasukkan Data');
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
        //
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
        $url = "http://127.0.0.1:8000/api/users/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('admin/pegawai')->withErrors($error);
        } else {
            $data = $contentArray['data'];
            return view('admin.pegawai', ['data' => $data]);
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
        $name = $request->name;
        $role = $request->role;
        $email = $request->email;
        $password = $request->password;

        $parameter = [
            'name' => $name,
            'role' => $role,
            'email' => $email,
            'password' => $password,
        ];

        $client = new Client();
        $url = "http://127.0.0.1:8000/api/users/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter),
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/pegawai')->withErrors($error);
        } else {
            return redirect()->to('admin/pegawai')->with('success', 'Berhasil Update Data');
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
        $url = "http://127.0.0.1:8000/api/users/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('admin/pegawai')->withErrors($error);
        } else {
            return redirect()->to('admin/pegawai')->with('success', 'Berhasil Delete Data');
        }
    }
}
