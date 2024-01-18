@extends('layouts.admin')

@section('name', 'Pegawai')

@section('content')
    <div id="layoutSidenav_content">
        <div class="container">
            <div class="main-content">
                <!-- TAMBAH PEGAWAI -->
                <form action='' method='post'>
                    @csrf
                    @if (Route::current()->uri == 'admin/pegawai/{id}')
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
                        <h2 class="mb-3">EDIT PEGAWAI</h2>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name='name' id="name"
                                    value="{{ isset($data['name']) ? $data['name'] : old('name') }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="role" aria-label="Default select example">
                                    <option value="pegawai"
                                        {{ isset($data['role']) && $data['role'] == 'pegawai' ? 'selected' : '' }}>
                                        Pegawai</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name='email' id="email"
                                    value="{{ isset($data['email']) ? $data['email'] : old('email') }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name='password' id="password"
                                    value="{{ isset($data['password']) ? $data['password'] : old('password') }}">
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

                <!-- AKHIR -->

                @if (Route::current()->uri == 'admin/pegawai')
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-1">No</th>
                                    <th class="col-md-2">Nama</th>
                                    <th class="col-md-2">Role</th>
                                    <th class="col-md-2">Email</th>
                                    <th class="col-md-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = $data['from']; ?>
                                @foreach ($data['data'] as $item)
                                    @if ($item['role'] === 'pegawai')
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['role'] }}</td>
                                            <td>{{ $item['email'] }}</td>
                                            <td>
                                                <a href="{{ url('admin/pegawai/' . $item['id']) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ url('admin/pegawai/' . $item['id']) }}" method="post"
                                                    onsubmit="return confirm('Apakah anda yakin ingin menghapusnya?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" name="submit"
                                                        class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        @if ($data['links'])
                            <ul class=" pagination justify-content-end">
                                @foreach ($data['links'] as $item)
                                    <li class="page-item {{ $item['active'] ? 'active' : '' }}"><a class="page-link"
                                            href="{{ $item['url2'] }}">{!! $item['label'] !!}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
