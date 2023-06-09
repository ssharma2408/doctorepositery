<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('patient_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'mobile_number' => [
                'string',
                'required',
                'unique:patients,mobile_number,' . request()->route('patient')->id,
            ],
            'clinic_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
