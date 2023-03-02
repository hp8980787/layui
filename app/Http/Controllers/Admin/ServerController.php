<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\ServerRequest;
use App\Http\Resources\ServerResource;
use App\Models\Country;
use App\Models\Server;
use Spatie\QueryBuilder\QueryBuilder;

class ServerController extends Controller
{
    public function index(ServerRequest $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 20;
            $servers = QueryBuilder ::for(Server ::query() -> with('country')) -> defaultSort('-id')
                -> paginate($perPage);
            return $this -> responseSuccess(ServerResource ::forCollection($servers), '');
        }
        return view('admin.servers.index');
    }

    public function create()
    {
        $countries = Country ::query() -> where('status', 1) -> get();
        return view('admin.servers.create', compact('countries'));
    }

    public function store(ServerRequest $request)
    {
        $data = $request -> only(['ip', 'name', 'user', 'country_id']);
        Server ::query() -> create($data);
        return $this -> responseSuccess('', '创建成功');
    }

    public function edit(ServerRequest $request, $id)
    {
        $server = Server ::query() -> findOrFail($id);
        $countries = Country ::query() -> where('status', 1) -> get();
        return view('admin.servers.edit', compact('server', 'countries'));
    }

    public function update(ServerRequest $request)
    {
        $id = $request -> id;
        $data = $request -> all();
        Server::query()->where('id',$id)->update($data);
        return $this->responseSuccess('','修改成功!');
    }

}
