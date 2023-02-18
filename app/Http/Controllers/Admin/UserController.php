<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 15;
            $users = QueryBuilder ::for(User::class) -> defaultSort('-id') -> paginate($perPage);
            return $this -> responseSuccess(UserResource ::forCollection($users));
        }
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }
}
