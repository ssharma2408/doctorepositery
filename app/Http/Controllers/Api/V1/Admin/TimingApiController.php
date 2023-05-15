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
}
