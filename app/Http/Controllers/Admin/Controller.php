<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\DataCollection;

class Controller extends BaseController
{
    protected $model;

    public function save(array $data)
    {
        $this -> model -> create($data);
    }

    public function responseSuccess($data = null, string $message = 'ok', int $count = 0, string $code = '0')
    {
        if ($data != null && gettype($data) != 'string') {
            return $data -> additional([
                'msg' => $message,
                'success' => true,
                'code' => '0',
            ]);
        }
        return [
            'data' => $data,
            'msg' => $message,
            'success' => true,
            'code' => '0',
        ];

    }
}
