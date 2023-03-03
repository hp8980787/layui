<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\DomainRequest;
use App\Http\Resources\DomainResource;
use App\Jobs\CheckDomainsExpired;
use App\Models\Country;
use App\Models\Domain;
use App\Models\Server;
use Illuminate\Database\Eloquent\Builder;
use Iodev\Whois\Factory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DomainController extends Controller
{
    public function index(DomainRequest $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 20;
            $domains = QueryBuilder ::for(Domain ::query() -> with(['country', 'server']))
                -> allowedSorts('id')
                -> allowedFilters([
                    AllowedFilter ::trashed(),
                    'name',
                    AllowedFilter ::callback('url', fn(Builder $query, $value) => $query -> where('name', 'like', "%$value%")
                        -> orWhere('url', 'like', "%$value%")),
                    AllowedFilter ::callback('country_id', fn(Builder $builder, $value) => $builder -> where('country_id', $value))
                ])
                -> paginate($perPage);
            return $this -> responseSuccess(DomainResource ::forCollection($domains));
        }

        $countries = Country ::query() -> where('status', 1) -> get();
        $servers = Server ::query() -> get();
        $deleteCount = Domain ::query() -> onlyTrashed() -> count();

        return view('admin.domains.index', compact('countries', 'servers', 'deleteCount'));
    }

    public function create()
    {
        $countries = Country ::query() -> where('status', 1) -> get();
        $servers = Server ::query() -> get();
        return view('admin.domains.create',compact('countries','servers'));
    }

    public function store(DomainRequest $request)
    {
        $data = $request -> all();
        Domain ::query() -> create($data);
        return $this -> responseSuccess('', '新增成功!');
    }


    public function check(DomainRequest $request)
    {
        $select = $request -> select;
        $id = $request -> id;
        $type = $request -> type;
        if ($select === 'all') {
            $domains = Domain ::query() -> get();
        } else {
            $domains = Domain ::query() -> whereIn('id', $id) -> get();
        }
        if ($type === 'expired') {
            CheckDomainsExpired ::dispatch($domains) -> onQueue('default');
        } else {

        }
        return $this -> responseSuccess('', '检查任务已创建!');
    }

    public function edit($id)
    {
        $domain = Domain ::query() -> with(['country', 'server']) -> findOrFail($id);
        $countries = Country ::query() -> where('status', 1) -> get();
        $servers = Server ::query() -> get();
        return view('admin.domains.edit', compact('domain', 'countries', 'servers'));
    }

    public function update(DomainRequest $request)
    {
        $id = $request -> id;
        $data = $request -> all();
        Domain ::query() -> where('id', $id) -> update($data);
        return $this -> responseSuccess('', '修改成功');
    }

    public function destroy(DomainRequest $request)
    {
        $id = $request -> id;
        $type = $request -> type;
        if ($type === 'real') {
            Domain ::query() -> whereIn('id', is_array($id) ? $id : [$id]) -> forceDelete();
        } else {
            Domain ::query() -> whereIn('id', is_array($id) ? $id : [$id]) -> delete();
        }

        return $this -> responseSuccess('', '删除成功!');
    }

    public function restore(DomainRequest $request)
    {
        $id = $request -> id;
        Domain ::withTrashed() -> whereIn('id', is_array($id) ? $id : [$id]) -> restore();
        return $this -> responseSuccess('', '恢复成功');
    }

}
