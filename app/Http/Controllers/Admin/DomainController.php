<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\DomainRequest;
use App\Http\Resources\DomainResource;
use App\Models\Domain;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DomainController extends Controller
{
    public function index(DomainRequest $request)
    {
        if ($request->isMethod('POST')) {
            $page = $request->page ?? 1;
            $perPage = $request->perPage ?? 20;
            $domains = QueryBuilder::for(Domain::query()->with('country'))->defaultSort('-id')
                ->allowedFilters([
                    AllowedFilter::trashed(),
                    'name',
                    'url',
                ])
                ->paginate($perPage);
            return $this->responseSuccess(DomainResource::forCollection($domains));
        }
        return view('admin.domains.index');
    }

    public function check(DomainRequest $request)
    {
        $type = $request->select;
        $id = $request->id;
        if ($type === 'all') {
            $domains = Domain::query()->get();
        } else {
            $domains = Domain::query()->whereIn('id', $id)->get();
        }
        return $this->responseSuccess('', '检查任务已创建!');
    }
}
