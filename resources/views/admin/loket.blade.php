@extends('layouts.admin')

@section('name', 'Loket')

@section('content')
    <div id="layoutSidenav_content">
        <div class="container">
            <!-- TAMBAH PEGAWAI -->
            <div class="main-content">
                <form action='' method='post'>
                    @csrf
                    @if (Route::current()->uri == 'admin/loket/{id}')
                        @method('put')
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif --}}
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <h2 class="mb-3">TAMBAH LOKET</h2>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='nama' id="nama"
                                    value="{{ isset($data['nama']) ? $data['nama'] : old('nama') }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jurusan" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10"><button type="submit" class="btn btn-primary"
                                    name="submit">SIMPAN</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- AKHIR -->

            @if (Route::current()->uri == 'admin/loket')
                <div class="main-content">
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-1">No</th>
                                    <th class="col-md-2">Nama</th>
                                    <th class="col-md-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <a href="{{ url('admin/loket/' . $item['id']) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ url('admin/loket/' . $item['id']) }}" method="post"
                                                onsubmit="return confirm('Apakah anda yakin ingin menghapusnya?')"
                                                class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" name="submit"
                                                    class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('sweetalert::alert')

@endsection
