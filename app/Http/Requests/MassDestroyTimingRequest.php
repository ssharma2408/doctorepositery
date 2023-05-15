<?php

namespace App\Http\Requests;

use App\Models\Timing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTimingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:timings,id',
        ];
    }
}
