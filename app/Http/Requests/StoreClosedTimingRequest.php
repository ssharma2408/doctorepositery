<?php

namespace App\Http\Requests;

use App\Models\ClosedTiming;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreClosedTimingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('closed_timing_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'closed_on' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
