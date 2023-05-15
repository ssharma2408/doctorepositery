<?php

namespace App\Http\Requests;

use App\Models\Timing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTimingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('timing_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'form' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'to' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'before_from' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'before_to' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'after_from' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'after_to' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
