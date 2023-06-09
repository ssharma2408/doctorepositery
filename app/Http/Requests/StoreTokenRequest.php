<?php

namespace App\Http\Requests;

use App\Models\Token;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
		//return Gate::allows('token_create');
    }

    public function rules()
    {
        return [
            'token_number' => [                
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
            ],
        ];
    }
}
