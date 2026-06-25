<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        // Return view or response for clients index
        return view('clients.index');
    }
}