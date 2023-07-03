<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePatientHistoryRequest;
use App\Http\Requests\UpdatePatientHistoryRequest;
use App\Http\Resources\Admin\PatientHistoryResource;
use App\Models\PatientHistory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientHistoryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('patient_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatientHistoryResource(PatientHistory::with(['patient', 'doctor'])->get());
    }

    public function store(StorePatientHistoryRequest $request)
    {
        $patientHistory = PatientHistory::create($request->all());

        return (new PatientHistoryResource($patientHistory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PatientHistory $patientHistory)
    {
        //abort_if(Gate::denies('patient_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatientHistoryResource($patientHistory->load(['patient', 'doctor']));
    }

    public function update(UpdatePatientHistoryRequest $request, PatientHistory $patientHistory)
    {
        $patientHistory->update($request->all());

        return (new PatientHistoryResource($patientHistory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PatientHistory $patientHistory)
    {
        abort_if(Gate::denies('patient_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientHistory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	
}
