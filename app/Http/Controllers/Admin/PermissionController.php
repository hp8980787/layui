<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(PermissionRequest $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 20;
            $data = Permission ::query() -> paginate($perPage);
            return $this -> responseSuccess(PermissionResource ::forCollection($data));

        }
        return view('admin.permissions.index');
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(PermissionRequest $request)
    {
        $name = $request -> name;
        $url = $request -> url;
        Permission ::query() -> create([
            'name' => $name,
            'url' => $url
        ]);

        return $this -> responseSuccess('', '添加成功');
    }

    public function edit($id)
    {
        $permission = Permission ::query() -> findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $request)
    {
        $id = $request -> id;
        $data = $request -> only(['name', 'url']);
        Permission ::query() -> where('id', $id) -> update($data);
        return $this -> responseSuccess('', '修改成功');
    }

    public function destroy(PermissionRequest $request)
    {
        $id = $request -> id;
        $id = is_array($id) ? $id : [$id];
        Permission ::query() -> whereIn('id', $id) -> delete();
        return $this->responseSuccess('','删除成功!');
    }
}
