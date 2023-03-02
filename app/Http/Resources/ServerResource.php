<?php

namespace App\Http\Resources;


class ServerResource extends JsonResource
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
            'ip' => $this -> ip,
            'user' => $this -> user,
            'country' => CountryResource ::make($this -> country),
            'created_at' => $this -> created_at,
            'editUrl'=>route('admin.servers.edit',[$this->id])
        ];
    }
}
