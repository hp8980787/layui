<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request -> isMethod('POST')) {
            return $this -> responseSuccess('');
        }
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(RoleRequest $request)
    {

    }
}
