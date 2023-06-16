<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPatientHistoryRequest;
use App\Http\Requests\StorePatientHistoryRequest;
use App\Http\Requests\UpdatePatientHistoryRequest;
use App\Models\Patient;
use App\Models\PatientHistory;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PatientHistoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('patient_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientHistories = PatientHistory::with(['patient', 'doctor'])->get();

        return view('admin.patientHistories.index', compact('patientHistories'));
    }

    public function create()
    {
        abort_if(Gate::denies('patient_history_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Patient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.patientHistories.create', compact('doctors', 'patients'));
    }

    public function store(StorePatientHistoryRequest $request)
    {
        $patientHistory = PatientHistory::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $patientHistory->id]);
        }

        return redirect()->route('admin.patient-histories.index');
    }

    public function edit(PatientHistory $patientHistory)
    {
        abort_if(Gate::denies('patient_history_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Patient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $patientHistory->load('patient', 'doctor');

        return view('admin.patientHistories.edit', compact('doctors', 'patientHistory', 'patients'));
    }

    public function update(UpdatePatientHistoryRequest $request, PatientHistory $patientHistory)
    {
        $patientHistory->update($request->all());

        return redirect()->route('admin.patient-histories.index');
    }

    public function show(PatientHistory $patientHistory)
    {
        abort_if(Gate::denies('patient_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientHistory->load('patient', 'doctor');

        return view('admin.patientHistories.show', compact('patientHistory'));
    }

    public function destroy(PatientHistory $patientHistory)
    {
        abort_if(Gate::denies('patient_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patientHistory->delete();

        return back();
    }

    public function massDestroy(MassDestroyPatientHistoryRequest $request)
    {
        $patientHistories = PatientHistory::find(request('ids'));

        foreach ($patientHistories as $patientHistory) {
            $patientHistory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('patient_history_create') && Gate::denies('patient_history_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new PatientHistory();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
