<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\SuratController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - redirect to surat index
     */
    public function index(Request $request)
    {
        $suratController = new SuratController();
        return $suratController->index($request);
    }
}

