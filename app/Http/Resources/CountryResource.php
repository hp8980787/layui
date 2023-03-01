<?php

namespace App\Http\Resources;


class CountryResource extends JsonResource
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
            'name' => $this -> name,
            'currency' => $this -> currency,
            'translations' => json_decode($this -> translations, true),
            'iso2' => $this -> iso2,
            'currency_symbol' => $this -> currency_symbol,
        ];
    }
}
