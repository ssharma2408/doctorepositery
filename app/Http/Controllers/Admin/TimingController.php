<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTimingRequest;
use App\Http\Requests\StoreTimingRequest;
use App\Http\Requests\UpdateTimingRequest;
use App\Models\Timing;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('timing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timings = Timing::with(['user'])->get();

        return view('admin.timings.index', compact('timings'));
    }

    public function create()
    {
        abort_if(Gate::denies('timing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.timings.create', compact('users'));
    }

    public function store(StoreTimingRequest $request)
    {
        $timing = Timing::create($request->all());

        return redirect()->route('admin.timings.index');
    }

    public function edit(Timing $timing)
    {
        abort_if(Gate::denies('timing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $timing->load('user');

        return view('admin.timings.edit', compact('timing', 'users'));
    }

    public function update(UpdateTimingRequest $request, Timing $timing)
    {
        $timing->update($request->all());

        return redirect()->route('admin.timings.index');
    }

    public function show(Timing $timing)
    {
        abort_if(Gate::denies('timing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timing->load('user');

        return view('admin.timings.show', compact('timing'));
    }

    public function destroy(Timing $timing)
    {
        abort_if(Gate::denies('timing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timing->delete();

        return back();
    }

    public function massDestroy(MassDestroyTimingRequest $request)
    {
        $timings = Timing::find(request('ids'));

        foreach ($timings as $timing) {
            $timing->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
