<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'openType' => $this -> open_type,
            'type' => $this -> type,
            'icon' => $this -> icon,
            'created_at' => $this -> created_at,

        ];
    }
}
