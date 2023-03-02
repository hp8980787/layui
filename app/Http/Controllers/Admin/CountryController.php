<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 20;
            $countries = QueryBuilder ::for(Country::class)
                -> allowedFilters([
                    'name',
                    'iso2',
                    'status',
                    AllowedFilter ::callback('search', fn(Builder $query, $value) => $query
                        -> where('name', 'like', "%value%") -> orWhere('iso2', 'like', "%$value%")
                        -> orWhere('currency', 'like', "%$value%")
                        -> orWhere('translations', 'like', "%$value%")
                    )
                ])
                -> paginate($perPage);
            return $this -> responseSuccess(CountryResource ::forCollection($countries));
        }
        return view('admin.countries.index');
    }

    public function update(Request $request)
    {
        $id = $request -> id;
        $data = $request -> only('status');
        Country ::query() -> where('id', $id) -> update($data);
        return $this->responseSuccess('','修改成功!');
    }
}
