<?php

namespace App\Http\Requests;

use App\Models\Doctor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doctor_edit');
    }

    public function rules()
    {
        return [
            'mobile_number' => [
                'string',
                'required',
                'unique:doctors,mobile_number,' . request()->route('doctor')->id,
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
