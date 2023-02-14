<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    protected $model;

    public function save(array $data)
    {
        $this -> model -> create($data);
    }

    public function responseSuccess( $data = null,string $message = 'ok',  int $count = 0, string $code = '0')
    {
        return response([
            'msg' => $message,
            'data' => $data,
            'count' => $count,
            'success' => true,
            'code' => '0',
        ], 200);
    }
}
