<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Base;

class JsonResource extends Base
{
    public static function forCollection($resource)
    {
        return new DataCollection($resource,static::class);
    }
}
