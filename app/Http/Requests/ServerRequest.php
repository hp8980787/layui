<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
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
        if ($action === 'store' || $action === 'update') {
            return [
                'ip' => 'required|ip',
                'name' => 'required|min:2,max:18',
                'user' => 'required|min:2,max:18',
                'country_id' => 'required'
            ];
        }
        return [
            //
        ];
    }
}
