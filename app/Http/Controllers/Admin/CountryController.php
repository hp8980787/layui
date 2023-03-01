<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            $page = $request->page ?? 1;
            $perPage = $request->perPage ?? 20;
            $countries = QueryBuilder::for(Country::class)->allowedFilters(['name', 'iso2'])
                ->paginate($perPage);
            return $this->responseSuccess(CountryResource::forCollection($countries));
        }
        return view('admin.countries.index');
    }
}
