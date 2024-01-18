<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PanggilController extends Controller
{
    const API_URL = "http://127.0.0.1:8001/api/antrians";

    public function show(Request $request)
    {
        $client = new Client();
        $loketId = $request->input('loket');
        $url = self::API_URL . '/?loket_id=' . $loketId;

        try {
            $response = $client->request('GET', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if (isset($contentArray['data'])) {
                $antrian = $contentArray['data'];

                // Mengambil semua data loket dari model Loket
                $loketOptions = Loket::all();

                // Mencari loket yang dipilih dari daftar loketOptions
                $selectedLoket = $loketOptions->firstWhere('loket_id', $loketId);

                // Filter hanya antrian dengan tanggal antrian sama dengan tanggal hari ini
                $today = now()->toDateString();
                $antrianHariIni = array_filter($antrian, function ($item) use ($today) {
                    return Carbon::createFromFormat('d/m/Y H:i:s', $item['created_at'])->toDateString() === $today;
                });


                return view('pegawai.panggil', ['data' => $antrianHariIni, 'loketOptions' => $loketOptions, 'selectedLoket' => $selectedLoket]);
            } else {
                $error = $contentArray['message'];
                return redirect()->to('/')->withErrors($error);
            }
        } catch (\Exception $e) {
            // Handle error jika permintaan gagal
            $errorMessage = $e->getMessage();
            return redirect()->to('/')->withErrors($errorMessage);
        }
    }

    public function displayyshow(Request $request)
    {
        $client = new Client();
        $loketId = $request->input('loket');
        $url = self::API_URL . '/?loket_id=' . $loketId;

        try {
            $response = $client->request('GET', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if (isset($contentArray['data'])) {
                $antrian = $contentArray['data'];

                // Mengambil semua data loket dari model Loket
                $loketOptions = Loket::all();

                // Mencari loket yang dipilih dari daftar loketOptions
                $selectedLoket = $loketOptions->firstWhere('loket_id', $loketId);

                return view('display', ['data' => $antrian, 'loketOptions' => $loketOptions, 'selectedLoket' => $selectedLoket]);
            } else {
                $error = $contentArray['message'];
                return redirect()->to('/')->withErrors($error);
            }
        } catch (\Exception $e) {
            // Handle error jika permintaan gagal
            $errorMessage = $e->getMessage();
            return redirect()->to('/')->withErrors($errorMessage);
        }
    }
}
