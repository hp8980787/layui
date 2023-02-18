<?php

namespace App\Http\Resources;


use Carbon\Carbon;

class MenuResource extends JsonResource
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
            'parent_id' => $this -> parent_id,
            'title' => $this -> title,
            'href' => $this -> getRawOriginal('href'),
            'openType' => $this -> open_type,
            'type' => $this -> type,
            'icon' => $this -> icon,
            'created_at' => Carbon ::make($this -> created_at) -> format('Y-m-d H:i:s'),
            'status' => $this -> status,
        ];
    }

}
