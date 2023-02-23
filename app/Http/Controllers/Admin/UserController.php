<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request)
    {
        $request -> validate([
            'name' => 'required|min:2|max:12',
            'email' => 'required|email',
            'password' => 'required',
            'nickname' => 'required|min:2|max:12',
        ]);
        $data = $request -> all();
        User ::query() -> create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nickname' => $data['nickname'],
            'password' => Hash ::make($data['password']),
        ]);
        return $this -> responseSuccess();
    }


    public function edit(Request $request)
    {

        $id = $request -> id;
        $user = User ::query() -> findOrFail($id);
        return view('admin.users.edit',compact('user'));
    }

    public function logout()
    {
        auth() -> logout();
        return redirect() -> back();
    }

    public function login(Request $request)
    {
        if ($request -> isMethod('POST')) {
            $request -> validate([
                'captcha' => 'required|captcha'
            ]);
            $name = $request -> name;
            $password = $request -> password;
            if (Auth ::attempt(compact('name', 'password'))) {
                return $this -> responseSuccess('登陆成功!');
            }
            return $this -> responseFail('账号密码不对');
        } else {
            return view('admin.users.login');
        }
    }
}
