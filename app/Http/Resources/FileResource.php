<?php

namespace App\Http\Resources;


use Carbon\Carbon;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this -> id,
            'name' => $this -> name,
            'thumb' => env('APP_URL') . $this -> path,
            'path' => public_path($this -> path),
            'type' => $this -> extension,
            'original_name' => $this -> original_name,
            'created_at' => Carbon ::make($this -> created_at) -> format('Y-m-d H:i:s'),
            'user' => [
                'id' => $this -> model -> id,
                'name' => $this -> model -> name,
                'nickname' => $this -> model -> nickname,
            ],
        ];
    }
}
