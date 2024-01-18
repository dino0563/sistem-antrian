@extends('layouts.super_admin')

@section('name', 'User Interface')

@section('content')
    <div id="layoutSidenav_content">
        <div class="container">
            <div class="card w-80 mb-3 mt-4">
                <div class="card-body">
                    <h5>Halaman Pengguna</h5>
                    <form action="{{ url('superadmin/user-interface/1') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- <label for="exampleColorInput" class="form-label">Pilih Warna</label>
                                            <input type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c"
                                                title="Choose your color"> -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" name="nama" id="nama"
                                    value="{{ isset($data['nama']) ? $data['nama'] : old('nama') }}">
                            </div>
                        </div>
                        <!-- edit.blade.php -->
                        <div class="form-group mb-3">
                            <label for="logo" class="mb-3">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control"
                                accept=".jpg, .jpeg, .png">
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" name="alamat" id="alamat"
                                    value="{{ isset($data['alamat']) ? $data['alamat'] : old('alamat') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Link instagram</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" name="instagram" id="instagram"
                                    value="{{ isset($data['instagram']) ? $data['instagram'] : old('instagram') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Link Website</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" name="website" id="website"
                                    value="{{ isset($data['website']) ? $data['website'] : old('website') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer> -->
    </div>
@endsection
