<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AmbilAntrianController extends Controller
{
    const API_URL = "http://127.0.0.1:8001/api/antrians";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('user.home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $loket = $request->loket_id;
        $nomor_antrian = $request->nomor_antrian;

        // Mengambil data dari API pertama
        $parameter1 = [
            'loket_id' => $loket,
            'nomor_antrian' => $nomor_antrian,
        ];

        $client1 = new Client();
        $url1 = "http://127.0.0.1:8001/api/antrians";

        $response1 = $client1->request('POST', $url1, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter1),
        ]);

        $content1 = $response1->getBody()->getContents();
        $contentArray1 = json_decode($content1, true);

        $nomor_antrian1 = $contentArray1['data']['nomor_antrian'];

        $client2 = new Client();
        $url2 = "http://127.0.0.1:8002/api/pages";

        $response2 = $client2->request('GET', $url2, [
            'headers' => ['Content-type' => 'application/json'],
        ]);

        $content2 = $response2->getBody()->getContents();
        $contentArray2 = json_decode($content2, true);

        $nama = $contentArray2['page'][0]['nama']; // Ambil data 'nama' dari respons API kedua

        // Gabungkan data dari kedua API
        $combinedData = [
            'nomor_antrian' => $nomor_antrian1,
            'pages' => $nama,
        ];
        // Generate PDF content
        $pdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf->setOptions($options);

        // Replace this with the path to your antrian-pdf.blade.php view
        $html = view('laporan.cetak', ['combinedData' => $combinedData])->render();

        $pdf->loadHtml($html);
        $pdf->setPaper([0, 0, 270, 400], 'landscape');
        $pdf->render();

        // Return the PDF as a response
        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="antrian.pdf"',
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $client = new Client();
        $url = self::API_URL;

        try {
            $response = $client->request('GET', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if (isset($contentArray['data'])) {
                $allAntrian = $contentArray['data'];

                $tanggalMulai = $request->input('tanggal_mulai');
                $tanggalAkhir = $request->input('tanggal_akhir');
                $selectedLoket = $request->input('loket');

                $antrian = array_filter($allAntrian, function ($item) use ($tanggalMulai, $tanggalAkhir, $selectedLoket) {
                    $antrianDate = \Carbon\Carbon::parse(date_create_from_format('d/m/Y H:i:s', $item['created_at'])->format('Y-m-d H:i:s'), 'Asia/Jakarta')->toDateString();

                    // Terapkan filter berdasarkan status
                    $status = $item['status'];
                    if ($status != 'Selesai Dilayani' && $status != 'Tidak Hadir') {
                        return false;
                    }

                    // Terapkan filter tanggal jika tanggal mulai dan tanggal akhir dipilih
                    if ($tanggalMulai && $tanggalAkhir) {
                        if (!($antrianDate >= $tanggalMulai && $antrianDate <= $tanggalAkhir)) {
                            return false;
                        }
                    }

                    // Terapkan filter jika loket dipilih
                    if ($selectedLoket && $item['loket_id'] != $selectedLoket) {
                        return false;
                    }

                    return true;
                });

                $loketOptions = Loket::all();

                // Terapkan filter jika loket dipilih
                $selectedLoket = $request->input('loket');
                if ($selectedLoket) {
                    $antrian = array_filter($antrian, function ($item) use ($selectedLoket) {
                        return $item['loket_id'] == $selectedLoket;
                    });
                }

                session(['filteredData' => $antrian]);

                return view('admin.laporan', [
                    'data' => $antrian,
                    'loketOptions' => $loketOptions,
                    'tanggalMulai' => $tanggalMulai,
                    'tanggalAkhir' => $tanggalAkhir,
                ]);
            } else {
                $error = $contentArray['message'];
                return redirect()->to('/')->withErrors($error);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika permintaan gagal
            $errorMessage = $e->getMessage();
            return redirect()->to('/')->withErrors($errorMessage);
        }
    }

    public function cetakPDF(Request $request)
    {
        $client = new Client();
        $url = self::API_URL;

        $client2 = new Client();
        $url2 = "http://127.0.0.1:8002/api/pages";

        $response2 = $client2->request('GET', $url2, [
            'headers' => ['Content-type' => 'application/json'],
        ]);

        $content2 = $response2->getBody()->getContents();
        $contentArray2 = json_decode($content2, true);

        $nama = $contentArray2['page'][0]['nama'];

        try {
            $response = $client->request('GET', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            $filteredData = session('filteredData');

            if (!empty($filteredData)) {
                $loketOptions = Loket::all();

                // Generate PDF
                $pdf = PDF::loadView('laporan.pdf', ['data' => $filteredData, 'loketOptions' => $loketOptions, 'name' => $nama]);

                // Download the PDF
                return $pdf->stream('laporan.pdf'); // Menggunakan download() untuk mengunduh PDF
            } else {
                $error = "Tidak ada data yang difilter.";
                return redirect()->to('/')->withErrors($error);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika permintaan gagal
            $errorMessage = $e->getMessage();
            return redirect()->to('/')->withErrors($errorMessage);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
