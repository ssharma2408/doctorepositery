<?php

namespace App\Http\Requests;

use App\Models\PatientHistory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePatientHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('patient_history_create');
    }

    public function rules()
    {
        return [
            'patient_id' => [
                'required',
                'integer',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
            'visit_date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'prescription' => [
                'string',
                'required',
            ],
        ];
    }
}
