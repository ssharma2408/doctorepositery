<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreClinicRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('clinic_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'address' => [
                'required',
            ],
            'package_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
