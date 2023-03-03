<?php

namespace App\Http\Resources;


use App\Models\Domain;
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
            'server_id' => $this -> server_id,
            'server' => $this -> when($this -> server_id, function () {
                return $this -> server;
            }),
            'check_status' => $this -> check_status,
            'country' => CountryResource ::make($this -> country),
            'created_at' => Carbon ::make($this -> created_at) -> format('Y-m-d H:i:s'),
            'expired_time' => $this -> expired_time,
            'expired_days' => $this -> when($this -> expired_time && $this -> expired_time != null, function () {
                return Carbon ::create($this -> expired_time) -> diffInDays(Carbon ::now());
            }),
            'expired_status' => Domain::STATUS_EXPIRED_GROUP[$this -> expired_status],
            'http_status' => Domain::STATUS_HTTP_GROUP[$this -> http_status],
        ];
    }
}
