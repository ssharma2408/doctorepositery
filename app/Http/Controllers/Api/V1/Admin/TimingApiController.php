<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimingRequest;
use App\Http\Requests\UpdateTimingRequest;
use App\Http\Resources\Admin\TimingResource;
use App\Models\Timing;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('timing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TimingResource(Timing::with(['user'])->get());
    }

    public function store(StoreTimingRequest $request)
    {
        $timing = Timing::create($request->all());

        return (new TimingResource($timing))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Timing $timing)
    {
        abort_if(Gate::denies('timing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TimingResource($timing->load(['user']));
    }

    public function update(UpdateTimingRequest $request, Timing $timing)
    {
        $timing->update($request->all());

        return (new TimingResource($timing))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Timing $timing)
    {
        abort_if(Gate::denies('timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timing->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function save(Request $request){
		
		
		$timings = Timing::where('user_id', $request->user_id)->get();
		if(count($timings)){
			//delete existing
			Timing::where('user_id', $request->user_id)->delete();
		}			
		$data = [];
		for($i=1; $i <= 7; $i++){
			for($j = 0; $j < count($request['open_'.$i]); $j++){
				if($request['open_'.$i][$j] != null && $request['close_'.$i][$j] != null){
					$timing = [];
					$timing['user_id'] = $request->user_id;
					$timing['clinic_id'] = $request->clinic_id;
					$timing['day'] = $i;
					$timing['start_hour'] = $request['open_'.$i][$j];
					$timing['end_hour'] = $request['close_'.$i][$j];
					$timing['max_token'] = isset($request['maxtoken_'.$i][$j])? $request['maxtoken_'.$i][$j] : 0;
					$timing['time_per_token'] = isset($request['timepertoken_'.$i][$j])? $request['timepertoken_'.$i][$j] : 0;
					$timing['deleted_at'] = null;
					$timing['created_at'] = date("Y-m-d H:i:s");
					$timing['updated_at'] = date("Y-m-d H:i:s");
					$data[] = $timing;
				}
			}			
		}
		if(!empty($data)){
			Timing::insert($data);
		}
		return (new TimingResource($timing))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
}
