<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyClinicRequest;
use App\Http\Requests\StoreClinicRequest;
use App\Http\Requests\UpdateClinicRequest;
use App\Models\Clinic;
use App\Models\Domain;
use App\Models\Package;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ClinicController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('clinic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::with(['package', 'clinic_admin', 'domain', 'doctors'])->get();

        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        abort_if(Gate::denies('clinic_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = Package::pluck('package', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clinic_admins = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Clinic Admin');
								}
							)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domains = Domain::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Doctor');
								}
							)->pluck('name', 'id');

        return view('admin.clinics.create', compact('clinic_admins', 'doctors', 'domains', 'packages'));
    }

    public function store(StoreClinicRequest $request)
    {
        $clinic = Clinic::create($request->all());
        $clinic->doctors()->sync($request->input('doctors', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $clinic->id]);
        }

        return redirect()->route('admin.clinics.index');
    }

    public function edit(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = Package::pluck('package', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clinic_admins = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Clinic Admin');
								}
							)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domains = Domain::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Doctor');
								}
							)->pluck('name', 'id');

        $clinic->load('package', 'clinic_admin', 'domain', 'doctors');

        return view('admin.clinics.edit', compact('clinic', 'clinic_admins', 'doctors', 'domains', 'packages'));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $clinic->update($request->all());
        $clinic->doctors()->sync($request->input('doctors', []));

        return redirect()->route('admin.clinics.index');
    }

    public function show(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->load('package', 'clinic_admin', 'domain', 'doctors');

        return view('admin.clinics.show', compact('clinic'));
    }

    public function destroy(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->delete();

        return back();
    }

    public function massDestroy(MassDestroyClinicRequest $request)
    {
        $clinics = Clinic::find(request('ids'));

        foreach ($clinics as $clinic) {
            $clinic->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('clinic_create') && Gate::denies('clinic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Clinic();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
