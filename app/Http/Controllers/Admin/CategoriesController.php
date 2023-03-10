<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;

class CategoriesController extends Controller
{
    public function index(CategoryRequest $request)
    {
        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 1;
            $categories = QueryBuilder ::for(Category::class) -> defaultSort('id')
                -> paginate($perPage);
            return $this -> responseSuccess(CategoryResource ::forCollection($categories));

        }
        return view('admin.categories.index');
    }

    public function edit($id)
    {
        $category = Category ::query() -> findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request)
    {
        $id = $request -> id;
        $data = $request -> all();
        Category ::query() -> where('id', $id) -> update($data);
        return $this -> responseSuccess('', '修改成功!');
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        Category::query()->create($request->all());
        return $this->responseSuccess('','添加成功!');
    }
}
