<?php

namespace App\Http\Requests;

use App\Models\Doctor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDoctorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doctor_create');
    }

    public function rules()
    {
        return [
            'mobile_number' => [
                'string',
                'required',
                'unique:doctors',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
            'clinics.*' => [
                'integer',
            ],
            'clinics' => [
                'array',
            ],
        ];
    }
}
