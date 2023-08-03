<?php

namespace App\Http\Requests;

use App\Models\Announcement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('announcement_create');
    }

    public function rules()
    {
        return [
            'clinic_id' => [
                'required',
                'integer',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
