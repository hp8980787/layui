<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DataCollection extends ResourceCollection
{

    public function __construct($resource, protected $class)
    {

        parent ::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this -> class ::collection($this -> collection),

            'count' => $this -> total(),
        ];
    }
}
