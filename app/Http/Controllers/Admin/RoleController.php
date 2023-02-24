<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            return $this->responseSuccess('');
        }
        return view('admin.roles.index');
    }
}
