<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\DomainRequest;
use App\Http\Resources\DomainResource;
use App\Jobs\CheckDomainsExpired;
use App\Models\Country;
use App\Models\Domain;
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
            $domains = QueryBuilder ::for(Domain ::query() -> with('country'))
                -> allowedSorts('expired_time')
                -> allowedFilters([
                    AllowedFilter ::trashed(),
                    'name',
                    'url',
                    AllowedFilter ::callback('country_id', fn(Builder $builder, $value) => $builder -> where('country_id', $value))
                ])
                -> paginate($perPage);
            return $this -> responseSuccess(DomainResource ::forCollection($domains));
        }

        $countries = Country ::query() -> where('status', 1) -> get();
        return view('admin.domains.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.domains.create');
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
}
