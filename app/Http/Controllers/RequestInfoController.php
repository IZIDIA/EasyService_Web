<?php

namespace App\Http\Controllers;

use App\Models\RequestInfo;
use Illuminate\Http\Request;

class RequestInfoController extends Controller
{
    public function index()
    {

        $request_infos = RequestInfo::all();

        return view('requests/index', ['request_infos' => $request_infos]);
    }
    public function create()
    {
        $request_info = new RequestInfo();
        return view('requests/create', compact('request_info'));
    }
}
