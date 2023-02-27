<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
                    'name'=>'required|min:2|max:30',
                ];
        }
        return [
            //
        ];
    }
}
