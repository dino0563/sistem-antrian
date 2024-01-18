@extends('layouts.admin')

@section('name', 'Laporan')

@section('content')
    <div id="layoutSidenav_content">
        <div class="container">
            <form action="{{ route('laporan.show') }}" method="get">
                @csrf
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="loket">Pilih Loket</label>
                            <select name="loket" id="loket" class="form-control">
                                <option value="">Semua Loket</option>
                                @foreach ($loketOptions as $loketOption)
                                    <option value="{{ $loketOption->id }}"
                                        {{ request('loket') == $loketOption->id ? 'selected' : '' }}>
                                        {{ $loketOption->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                value="{{ $tanggalMulai }}">
                        </div>
                    </div>
                    <!-- Tanggal Akhir -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                                value="{{ $tanggalAkhir }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="main-content">
                <div class="card w-80 mb-3 mt-3">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nomor Antrian</th>
                                    <th scope="col">Loket</th>
                                    <th scope="col">Waktu Pengambilan</th>
                                    <th scope="col">Waktu Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['nomor_antrian'] }}</td>
                                        <td>{{ $item['loket_nama'] }}</td>
                                        <td>{{ $item['created_at'] }}</td>
                                        <td>{{ $item['updated_at'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form action="{{ route('laporan.pdf') }}" method="get" target="_blank">
                            <!-- Form fields for filtering, Loket dropdown, etc. -->
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
    @endphp

@endsection
