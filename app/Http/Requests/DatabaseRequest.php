<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatabaseRequest extends FormRequest
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
        switch ($action) {
            case 'store':
                return [
                    'host' => 'required|ip',
                    'port' => 'required',
                    'database' => 'required',
                    'username' => 'required',
                    'password' => 'required',
                ];
        }

        return [
            //
        ];
    }
}
