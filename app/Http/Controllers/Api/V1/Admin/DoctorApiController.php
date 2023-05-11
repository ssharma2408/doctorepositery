<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\Admin\DoctorResource;
use App\Models\Doctor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doctor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DoctorResource(Doctor::with(['clinic'])->get());
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->all());

        return (new DoctorResource($doctor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DoctorResource($doctor->load(['clinic']));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->all());

        return (new DoctorResource($doctor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
