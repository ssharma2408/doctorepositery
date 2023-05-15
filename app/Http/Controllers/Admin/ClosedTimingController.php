<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClosedTimingRequest;
use App\Http\Requests\StoreClosedTimingRequest;
use App\Http\Requests\UpdateClosedTimingRequest;
use App\Models\ClosedTiming;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClosedTimingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('closed_timing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closedTimings = ClosedTiming::with(['user'])->get();

        return view('admin.closedTimings.index', compact('closedTimings'));
    }

    public function create()
    {
        abort_if(Gate::denies('closed_timing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.closedTimings.create', compact('users'));
    }

    public function store(StoreClosedTimingRequest $request)
    {
        $closedTiming = ClosedTiming::create($request->all());

        return redirect()->route('admin.closed-timings.index');
    }

    public function edit(ClosedTiming $closedTiming)
    {
        abort_if(Gate::denies('closed_timing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $closedTiming->load('user');

        return view('admin.closedTimings.edit', compact('closedTiming', 'users'));
    }

    public function update(UpdateClosedTimingRequest $request, ClosedTiming $closedTiming)
    {
        $closedTiming->update($request->all());

        return redirect()->route('admin.closed-timings.index');
    }

    public function show(ClosedTiming $closedTiming)
    {
        abort_if(Gate::denies('closed_timing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closedTiming->load('user');

        return view('admin.closedTimings.show', compact('closedTiming'));
    }

    public function destroy(ClosedTiming $closedTiming)
    {
        abort_if(Gate::denies('closed_timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closedTiming->delete();

        return back();
    }

    public function massDestroy(MassDestroyClosedTimingRequest $request)
    {
        $closedTimings = ClosedTiming::find(request('ids'));

        foreach ($closedTimings as $closedTiming) {
            $closedTiming->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
