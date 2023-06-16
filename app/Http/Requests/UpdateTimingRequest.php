<?php

namespace App\Http\Requests;

use App\Models\Timing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTimingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('timing_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'start_hour' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'end_hour' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
