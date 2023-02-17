<?php

namespace App\Http\Controllers\Admin\Tarit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Tree
{
    public array $data = [];


    public function getDataTree($parentId = null, $level = 0)
    {

        $data = $this -> model -> where('parent_id', $parentId) -> get();
        foreach ($data as &$val) {
            if ($level > 0) {
                $val -> title = str_repeat('--', $level) . $val -> title;
            }

            $val['children'] = $this -> getDataTree($val['id'], $level + 1);
        }

        return $data;
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
