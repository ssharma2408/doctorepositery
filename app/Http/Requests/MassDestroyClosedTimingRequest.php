<?php

namespace App\Http\Requests;

use App\Models\ClosedTiming;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyClosedTimingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('closed_timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:closed_timings,id',
        ];
    }
}
