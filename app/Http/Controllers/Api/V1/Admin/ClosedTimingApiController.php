<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClosedTimingRequest;
use App\Http\Requests\UpdateClosedTimingRequest;
use App\Http\Resources\Admin\ClosedTimingResource;
use App\Models\ClosedTiming;
use App\Models\Clinic;
use App\Models\Timing;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClosedTimingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('closed_timing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClosedTimingResource(ClosedTiming::with(['user'])->get());
    }

    public function store(StoreClosedTimingRequest $request)
    {
        $closedTiming = ClosedTiming::create($request->all());

        return (new ClosedTimingResource($closedTiming))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ClosedTiming $closedTiming)
    {
        abort_if(Gate::denies('closed_timing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClosedTimingResource($closedTiming->load(['user']));
    }

    public function update(UpdateClosedTimingRequest $request, ClosedTiming $closedTiming)
    {
        $closedTiming->update($request->all());

        return (new ClosedTimingResource($closedTiming))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ClosedTiming $closedTiming)
    {
        abort_if(Gate::denies('closed_timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closedTiming->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_closed_day($user_id){		
		return new ClosedTimingResource(ClosedTiming::where('user_id', $user_id)->get());
	}
	
	public function check_closed($clinic_id){		

		$is_clinic_closed = false;

		$clinic = Clinic::find($clinic_id);
		$record = ClosedTiming::where(['user_id'=>$clinic->clinic_admin_id, 'closed_on'=>date('Y-m-d')])->get();
		
		$timing = Timing::where(['user_id'=>$clinic->clinic_admin_id, 'day'=>date( 'N' )])->get();
		
		if(count($record) || ! count($timing)){
			$is_clinic_closed = true;
		}
		$status = ["is_clinic_closed"=>$is_clinic_closed];
		
		return new ClosedTimingResource($status);
				
	}
	
	public function save(Request $request){
		
		$closed_timings = ClosedTiming::where('user_id', $request->user_id)->get();
		if(count($closed_timings)){
			//delete existing
			ClosedTiming::where('user_id', $request->user_id)->delete();
		}			
		$data = [];
		
		for($j = 0; $j < count($request['closedon']); $j++){
			if($request['closedon'][$j] != null){
				$closedTiming = [];
				$closedTiming['user_id'] = $request->user_id;
				$closedTiming['closed_on'] = $request['closedon'][$j];
				$closedTiming['deleted_at'] = null;
				$closedTiming['created_at'] = date("Y-m-d H:i:s");
				$closedTiming['updated_at'] = date("Y-m-d H:i:s");
				$data[] = $closedTiming;
			}
		}			
		
		if(!empty($data)){
			ClosedTiming::insert($data);
		}
		return (new TimingResource($closedTiming))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
}
