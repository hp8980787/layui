<?php

namespace App\Models\Tarit;

use Illuminate\Database\Eloquent\Builder;

trait ModelFilterTrait
{
    protected $builder;

    /**
     *模型查询条件过滤
     *
     **/
    public function apply(array $data)
    {
        $this -> builder = static ::query();
        foreach ($data as $key => $value) {
            if (method_exists($this, $key)) {
                call_user_func_array([$this, $key], array_filter([$value]));
            }
        }
        return $this -> builder;
    }
}
