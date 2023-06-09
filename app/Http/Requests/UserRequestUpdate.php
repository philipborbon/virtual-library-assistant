<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestUpdate extends UserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'library_id' => "required|unique:users,library_id," . request()->id,
            'classification' => 'required',
            // 'password' => 'required',
        ];
    }
}
