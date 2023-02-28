<?php

namespace App\Http\Resources;


use Carbon\Carbon;

class DomainResource extends JsonResource
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
            'url' => $this -> url,
            'check_status' => $this -> check_status,
            'country' => CountryResource ::make($this -> country),
            'created_at' => Carbon ::make($this -> created_at) -> format('Y-m-d H:i:s'),
        ];
    }
}
