<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePatientHistoryRequest;
use App\Http\Requests\UpdatePatientHistoryRequest;
use App\Http\Resources\Admin\PatientHistoryResource;
use App\Models\PatientHistory;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\User;
use Gate;
use DB;
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
	public function patient_history($id)
	{
		$member_ids = [];
		$members = Patient::where('family_id', $id)->get(['id', 'name']);
		
		foreach($members as $member){
			$member_ids[] = $member->id;
		}
		
		$clinics = Clinic::join('patient_histories', 'clinics.id', '=', 'patient_histories.clinic_id')->whereIn('patient_histories.patient_id', $member_ids)->groupBy('clinics.id')
                ->get(['clinics.id', 'clinics.name']);
		
		$doctors = User::join('patient_histories', 'users.id', '=', 'patient_histories.doctor_id')->whereIn('patient_histories.patient_id', $member_ids)->groupBy('users.id')
				->get(['users.id', 'users.name']);
				
		return new PatientHistoryResource(['members'=>$members, 'clinics'=>$clinics, 'doctors'=>$doctors]);
	}
	
	public function get_history(Request $request){
		if(empty($request->member) || $request->member == ""){
			return;
		}
		
		$condition = "";
		
		if($request->clinic != ""){
			$condition .= " AND clinic_id =".$request->clinic;
		}
		if($request->doctor != ""){
			$condition .= " AND doctor_id =".$request->doctor;
		}
		
		$history = DB::select('SELECT visit_date, prescription, comment, next_visit_date
								FROM patient_histories								
								WHERE patient_id =?
								'.$condition.'
								GROUP BY id
								ORDER BY id DESC;', [$request->member]);		
		
		return new PatientHistoryResource($history);
	}
	
}
