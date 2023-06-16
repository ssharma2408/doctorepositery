<?php

namespace App\Http\Requests;

use App\Models\Token;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTokenRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('token_edit');
    }

    public function rules()
    {
        return [
            'token_number' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'clinic_id' => [
                'required',
                'integer',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
            'patient_id' => [
                'required',
                'integer',
            ],
            'estimated_time' => [
                'string',
                'required',
            ],
        ];
    }
}
