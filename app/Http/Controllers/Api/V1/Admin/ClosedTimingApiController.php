<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClosedTimingRequest;
use App\Http\Requests\UpdateClosedTimingRequest;
use App\Http\Resources\Admin\ClosedTimingResource;
use App\Models\ClosedTiming;
use App\Models\Clinic;
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
		$clinic = Clinic::find($clinic_id);
		$record = ClosedTiming::where(['user_id'=>$clinic->clinic_admin_id, 'closed_on'=>date('Y-m-d')])->get();		
		return new ClosedTimingResource($record);		
	}
}
