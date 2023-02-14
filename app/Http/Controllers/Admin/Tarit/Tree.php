<?php

namespace App\Http\Controllers\Admin\Tarit;

trait Tree
{
    protected array $data = [];

    public function getDataTree($parentId = null)
    {
        $data = $this -> model -> where('parent_id', $parentId) -> get() -> toArray();
        foreach ($data as &$val) {
            $val['children'] = $this -> getDataTree($val['id']);
        }
        $this -> data = $data;
        return $this;
    }

    public function toJson(): string
    {
        return json_encode($this -> data);
    }

    public function toArray(): array
    {
        return $this -> data;
    }
}
