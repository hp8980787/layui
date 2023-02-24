<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\MenuRequest;
use App\Http\Resources\DataCollection;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Http\Controllers\Admin\Tarit\Tree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class MenuController extends Controller
{
    use Tree;

    public function __construct(Menu $menu)
    {
        $this -> model = $menu;
    }

    public function index(MenuRequest $request)
    {

        if ($request -> isMethod('POST')) {
            $page = $request -> page ?? 1;
            $perPage = $request -> perPage ?? 8;

            if ($request -> restore && $id = $request -> id) {
                Menu ::query() -> withTrashed() -> whereIn('id', is_array($id) ? $id : [$id]) -> restore();
            }
            $data = QueryBuilder ::for(Menu::class)
                -> defaultSort('-id')
                -> allowedSorts(['id'])
                -> allowedFilters([
                    AllowedFilter ::trashed(),
                    'title',
                ]) -> paginate($perPage);
            return $this -> responseSuccess(MenuResource ::forCollection($data));
        }
        $trashCount = Menu ::onlyTrashed() -> count();
        return view('admin.menus.index', compact('trashCount'));
    }

    public function create()
    {
        $menusTree = $this -> getDataTree();
        return view('admin.menus.add', compact('menusTree'));
    }

    public function store(MenuRequest $request)
    {
        try {
            /**
             *保存时保准一致性
             **/
            DB ::beginTransaction();
            $parentId = $request -> parent_id;
//            if ($parentId) {
//                $menu = Menu ::query() -> findOrFail($parentId);
//                $menu -> type = 1;
//                $menu -> save();
//            }
            $this -> save($request -> input());
            DB ::commit();
        } catch (\Exception $exception) {
            DB ::rollBack();
        }

        return $this -> responseSuccess('添加成功!');
    }

    public function edit(Request $request)
    {
        $id = $request -> id;
        if ($request -> isMethod('POST')) {
            $request -> validate([
                'title' => 'required|min:2|max:20',
            ]);
            $data = $request -> only((new Menu()) -> getFillable());
            Menu ::query() -> where('id', $id) -> update($data);
            return $this -> responseSuccess('成功!');
        }
        $menu = Menu ::query() -> findOrFail($id);
        $menusTree = $this -> getDataTree(null,0,'--');
        return view('admin.menus.edit', compact('menu', 'menusTree'));
    }

    /**
     *删除menu
     **/
    public function destroy(Request $request)
    {

        $id = $request -> id;
        $menus = Menu ::query() -> withTrashed() -> whereIn('id', is_array($id) ? $id : [$id]) -> get();
        foreach ($menus as $menu) {
            if ($menu -> trashed()) {
                //完全删除
                $menu -> forceDelete();
            } else {
                $menu -> delete();
            }

        }
        return $this -> responseSuccess('删除成功!');
    }

    /**
     *所有后台
     *
     **/
    public function all()
    {
        $menus = $this->getDataTree(null,0,'');
        return response($menus);
    }
}
