<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Http\Controllers\Admin\Tarit\Tree;

class MenuController extends Controller
{
    use Tree;

    public function __construct(Menu $menu)
    {
        $this -> model = $menu;
    }

    public function index(MenuRequest $request)
    {
        if ($request ->isJson()) {
            $page = $request->page ?? 1;
            $perPage = $request->per_page ?? 8;
            $data = Menu::query()->paginate();
            return $this->responseSuccess(MenuResource::collection($data));
        }

        return view('admin.menus.index');
    }

    public function create()
    {
        $menusTree = $this -> getDataTree()->toJson();
        dd($menusTree);
        return view('admin.menus.add', compact('menusTree'));
    }

    public function store(MenuRequest $request)
    {

        $this -> save($request -> input());
        return $this -> responseSuccess('添加成功!');
    }
}
