<?php

namespace App\Http\Requests;

use App\Models\Domain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDomainRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('domain_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:domains',
            ],
        ];
    }
}
