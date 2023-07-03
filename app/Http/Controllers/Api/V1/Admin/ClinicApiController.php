<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreClinicRequest;
use App\Http\Requests\UpdateClinicRequest;
use App\Http\Resources\Admin\ClinicResource;
use App\Models\Clinic;
use App\Models\Timing;
use App\Models\User;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Input;

class ClinicApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        //abort_if(Gate::denies('clinic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClinicResource(Clinic::with(['package', 'clinic_admin', 'domain', 'doctors'])->get());
    }

    public function store(StoreClinicRequest $request)
    {
        $clinic = Clinic::create($request->all());
        $clinic->doctors()->sync($request->input('doctors', []));

        return (new ClinicResource($clinic))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Clinic $clinic)
    {
        //abort_if(Gate::denies('clinic_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$timings = Timing::where('user_id', $clinic->clinic_admin->id)->orderBy('start_hour')->get();

		$clinic->opening_hours = $timings;

        return new ClinicResource($clinic->load(['package', 'clinic_admin', 'domain', 'doctors']));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $clinic->update($request->all());
        $clinic->doctors()->sync($request->input('doctors', []));

        return (new ClinicResource($clinic))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function doctors(Request $request){
		return new ClinicResource(Clinic::where('id', $request->clinic_id)->with(['doctors'])->get());
	}
	
	public function get_doctor(Request $request){
		$timings = Timing::where('user_id', $request->doctor_id)->orderBy('start_hour')->get();
		$doctor = User::where('id', $request->doctor_id)->first();
		
		$final_arr = array('opening_hours'=>$timings, 'doctor'=>$doctor);		

		return new ClinicResource($final_arr);
	}
	
	public function doctors_timing(Request $request){
		
		$doctors = DB::select('SELECT u.id, u.name, t.day, t.start_hour, t.end_hour, t.id as slot_id
								FROM clinics c 
								INNER JOIN clinic_user cu on c.id=cu.clinic_id 
								INNER JOIN users u on u.id = cu.user_id 
								INNER JOIN timings t on t.user_id = u.id 
								WHERE c.id = ?
								ORDER BY t.id
								', [$request->clinic_id]);
		
		return new ClinicResource($doctors);
	}
	
	public function get_timings(Request $request){		
		
		$clinic = Clinic::where('id', $request->clinic_id)->first();
		$timings = Timing::where('user_id', $clinic->clinic_admin->id)->orderBy('start_hour')->get();		

		$final_arr = array('opening_hours'=>$timings, 'clinic_user'=>$clinic->clinic_admin->id);

		return new ClinicResource($final_arr);
	}
	
}
