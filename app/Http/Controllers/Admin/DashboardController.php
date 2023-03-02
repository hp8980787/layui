<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function index()
    {
        return view('index');
    }


    public function clear(Request $request)
    {
        return response() -> json([
            'code' => 0,
            'success' => true,
            'msg' => 'success',
        ]) -> withHeaders([
            "Cache-Control" => " no-cache, must-revalidate"
        ]);
    }
}
