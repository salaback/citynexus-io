<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name')->get();

        return view('admin.index', compact('clients'));
    }

}
