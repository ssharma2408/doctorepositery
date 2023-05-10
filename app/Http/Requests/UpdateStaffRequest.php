<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('staff_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:staffs,email,' . request()->route('staff')->id,
            ],
            'mobile_number' => [
                'string',
                'required',
                'unique:staffs,mobile_number,' . request()->route('staff')->id,
            ],
            'username' => [
                'string',
                'required',
                'unique:staffs,username,' . request()->route('staff')->id,
            ],
        ];
    }
}
