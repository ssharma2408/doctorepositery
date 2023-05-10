<?php

namespace App\Http\Requests;

use App\Models\Package;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePackageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('package_edit');
    }

    public function rules()
    {
        return [
            'package' => [
                'string',
                'required',
                'unique:packages,package,' . request()->route('package')->id,
            ],
        ];
    }
}
