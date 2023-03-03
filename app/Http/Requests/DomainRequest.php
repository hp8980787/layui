<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $action = explode('@', $this -> route() -> getActionName())[1];

        if ($action === 'store') {
            return [
                'server_id' => 'required',
                'country_id' => 'required',
                'name' => 'required',
                'url' => 'required|url',
            ];
        }
        return [
            //
        ];
    }
}
