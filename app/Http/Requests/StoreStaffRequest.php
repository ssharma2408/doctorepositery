<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('staff_create');
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
                'unique:staffs',
            ],
            'mobile_number' => [
                'string',
                'required',
                'unique:staffs',
            ],
            'username' => [
                'string',
                'required',
                'unique:staffs',
            ],
            'password' => [
                'required',
            ],
            'clinic_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
