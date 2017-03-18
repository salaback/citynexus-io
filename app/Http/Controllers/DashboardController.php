<?php

namespace App\Http\Controllers;

/**/use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $widgets = Auth::getUser()->widgets;
        return view('dashboard.index', compact('widgets'));
    }
}
