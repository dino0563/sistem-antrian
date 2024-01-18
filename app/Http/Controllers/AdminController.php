<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }
    public function pegawai()
    {
        return view('pegawai.home');
    }
    public function superadmin()
    {
        return view('superadmin.home');
    }
}
