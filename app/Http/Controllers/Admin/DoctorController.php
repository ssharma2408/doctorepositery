<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDoctorRequest;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doctor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctors = Doctor::with(['clinic', 'doctor'])->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        abort_if(Gate::denies('doctor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Doctor');
								}
							)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.doctors.create', compact('clinics', 'doctors'));
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->all());

        return redirect()->route('admin.doctors.index');
    }

    public function edit(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Doctor');
								}
							)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctor->load('clinic', 'doctor');

        return view('admin.doctors.edit', compact('clinics', 'doctor', 'doctors'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->all());

        return redirect()->route('admin.doctors.index');
    }

    public function show(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctor->load('clinic', 'doctor');

        return view('admin.doctors.show', compact('doctor'));
    }

    public function destroy(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctor->delete();

        return back();
    }

    public function massDestroy(MassDestroyDoctorRequest $request)
    {
        $doctors = Doctor::find(request('ids'));

        foreach ($doctors as $doctor) {
            $doctor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
