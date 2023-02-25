<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
//    protected $stopOnFirstFailure = true;

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
                    'name' => 'required|min:2|max:15'
                ];
        }
        return [
            //
        ];
    }

    /**
     * 配置验证实例。
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator -> after(function ($validator) {
            foreach ($validator->errors() as $error){
                dd($error->message);
            }
        });
    }
}
