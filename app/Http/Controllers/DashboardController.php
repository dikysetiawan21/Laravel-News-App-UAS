<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Tampilkan view dashboard utama
        return view('dashboard');
    }
}
