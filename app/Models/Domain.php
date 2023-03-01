<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    const EXPIRED_STATUS_FAIL = 0;
    const EXPIRED_STATUS_SUCCESS = 1;

    const HTTP_STATUS_FAIL = 0;
    const HTTP_STATUS_SUCCESS = 1;

    const STATUS_EXPIRED_GROUP = [
        self::EXPIRED_STATUS_FAIL => '失败',
        self::EXPIRED_STATUS_SUCCESS => '成功'
    ];
    const STATUS_HTTP_GROUP = [
        self::HTTP_STATUS_SUCCESS => '成功',
        self::HTTP_STATUS_FAIL => '失败',
    ];

    public function country()
    {
        return $this -> belongsTo(Country::class, 'country_id', 'id');
    }

}
