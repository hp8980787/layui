<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(RoleRequest $request)
    {
        if ($request -> isMethod('POST')) {
            $perPage = $request -> perPage ?? 20;
            $page = $request -> page ?? 1;
            $roles = Role ::query() -> paginate($perPage);
            return $this -> responseSuccess(RoleResource ::forCollection($roles));
        }
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(RoleRequest $request)
    {
        $name = $request -> name;
        Role ::create(['name' => $name]);
        return $this -> responseSuccess('');
    }

    public function edit($id)
    {
        $role = Role ::query() -> findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(RoleRequest $request)
    {
        $id = $request -> id;
        $name = $request -> name;
        $role = Role ::query() -> findOrFail($id);
        $role -> name = $name;
        $role -> save();
        return $this -> responseSuccess('', '修改成功!');
    }

    public function destroy(RoleRequest $request)
    {
        $id = $request -> id;
        $id = is_array($id) ? $id : [$id];
        Role ::query() -> whereIn('id', $id) -> delete();
        return $this -> responseSuccess('','删除成功');
    }
}
