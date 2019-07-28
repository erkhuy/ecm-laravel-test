<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email,'.$this->id,
            'role' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => ' * Họ tên không được để trống',
            'address.required' => ' * Địa chỉ không được để trống',
            'phone.required' => '* Số điện thoại không được để trống',
            'phone.numeric' => ' * Số điện thoại phải là số',
            'email.required' => ' * Email không được để trống',
            'email.email' => '* Email không đúng định dạng Example@domail.somthing',
            'email.unique' => '* Email đã được sử dụng! Chon Email khác.',
            'role.required' => '* Chọn quyền',

        ];
    }
}
