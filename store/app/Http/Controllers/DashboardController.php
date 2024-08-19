<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = 'Mohammed Alsaifi';
        return view('dashboard.index', compact('user'));
    }
}
