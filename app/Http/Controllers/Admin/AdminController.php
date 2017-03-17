<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{

    public function __construct(Request $request)
    {
        if ($request->user()->cannot('citynexus', ['superAdmin', 'view'])) {
            App::abort(403, 'Access denied');
        }

    }

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
