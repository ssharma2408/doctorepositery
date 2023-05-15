<?php

namespace App\Http\Requests;

use App\Models\ClosedTiming;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateClosedTimingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('closed_timing_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'full_partial' => [
                'required',
            ],
            'closed_on' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'from' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'to' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
