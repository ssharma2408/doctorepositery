<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOpeningHourRequest;
use App\Http\Requests\StoreOpeningHourRequest;
use App\Http\Requests\UpdateOpeningHourRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class OpeningHoursController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('opening_hour_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');		

		$users = User::with('roles')->get();
		$user_arr = [];
		foreach($users as $user){
			if($user->roles[0]->title != "Super Admin"){
				$user_arr[str_replace(' ', '_', $user->roles[0]->title)][] = $user;
			}
		}		
		
        return view('admin.openingHours.index', compact('user_arr'));
    }

    public function create()
    {
        abort_if(Gate::denies('opening_hour_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.openingHours.create');
    }

    public function store(StoreOpeningHourRequest $request)
    {
        $openingHour = OpeningHour::create($request->all());

        return redirect()->route('admin.opening-hours.index');
    }

    public function edit(OpeningHour $openingHour)
    {
        abort_if(Gate::denies('opening_hour_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.openingHours.edit', compact('openingHour'));
    }

    public function update(UpdateOpeningHourRequest $request, OpeningHour $openingHour)
    {
        $openingHour->update($request->all());

        return redirect()->route('admin.opening-hours.index');
    }

    public function show(OpeningHour $openingHour)
    {
        abort_if(Gate::denies('opening_hour_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.openingHours.show', compact('openingHour'));
    }

    public function destroy(OpeningHour $openingHour)
    {
        abort_if(Gate::denies('opening_hour_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $openingHour->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpeningHourRequest $request)
    {
        $openingHours = OpeningHour::find(request('ids'));

        foreach ($openingHours as $openingHour) {
            $openingHour->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
