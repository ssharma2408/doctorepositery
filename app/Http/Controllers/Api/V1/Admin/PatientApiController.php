<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\Admin\PatientResource;
use App\Models\Patient;
use App\Models\Dependent;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('patient_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatientResource(Patient::with(['clinic'])->get());
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->all());

        return (new PatientResource($patient))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Patient $patient)
    {
        abort_if(Gate::denies('patient_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatientResource($patient->load(['clinic']));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $patient->update($request->all());

        return (new PatientResource($patient))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Patient $patient)
    {
        abort_if(Gate::denies('patient_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_patients(Request $request)
	{
		$patients = DB::select('SELECT p.id, p.name, ph.visit_date, ph.id as history_id
								FROM patients p 
								INNER JOIN patient_histories ph on p.id=ph.patient_id 								
								WHERE ph.doctor_id = ?
								ORDER BY p.id
								', [$request->doctor_id]);
		
		return new PatientResource($patients);
	}
	
	public function get_members(Request $request)
	{
		$members = Patient::where('family_id', $request->family_id)->get();
		
		return new PatientResource($members);
	}
	
	public function add_member(Request $request)
	{
		if(isset($request->mobile_number)){
			$member = Patient::create($request->all());			
		}else{
			$member = Dependent::create($request->all());
		}
		return (new PatientResource($member))
				->response()
				->setStatusCode(Response::HTTP_CREATED);
	}
}
