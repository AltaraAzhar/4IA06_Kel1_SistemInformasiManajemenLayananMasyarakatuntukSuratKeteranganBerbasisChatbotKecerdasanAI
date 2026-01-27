<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayananController extends Controller
{
    /**
     * Menampilkan halaman informasi layanan (public, tanpa login)
     */
    public function index()
    {
        return view('layanan.index');
    }
}

