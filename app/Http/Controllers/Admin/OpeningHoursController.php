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
use App\Models\Timing;

class OpeningHoursController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('opening_hour_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');		

		$user_arr = [];
		$timing_arr = [];
		
		$users = User::with('roles')->get();			
		
		$timings = Timing::orderBy('start_hour')->get();
		
		if(!empty($timings)){
			foreach($timings as $timing){
				$timing_arr[$timing['user_id']][$timing['day']][] = $timing;
			}
		}
		
		foreach($users as $user){
			if($user->roles[0]->title != "Super Admin"){
				if(!empty($timing_arr[$user->id])){
					$user->timings = $timing_arr[$user->id];
				}else{
					$user->timings = [];
				}
				$user_arr[str_replace(' ', '_', $user->roles[0]->title)][] = $user;
			}
		}
		
		$user_id = isset($request->user_id) ? $request->user_id : "";
		$use_role = isset($request->role) ? $request->role : "";		

		$day_arr = array("Monday", "Tuseday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

        return view('admin.openingHours.index', compact('user_arr', 'day_arr', 'user_id', 'use_role'));
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
					$timing['clinic_id'] = 0;
					$timing['max_token'] = isset($request['maxtoken_'.$i][$j])? $request['maxtoken_'.$i][$j] : 0;
					$timing['time_per_token'] = isset($request['timepertoken_'.$i][$j])? $request['timepertoken_'.$i][$j] : 0;
					$timing['day'] = $i;
					$timing['start_hour'] = $request['open_'.$i][$j];
					$timing['end_hour'] = $request['close_'.$i][$j];
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
		return redirect()->route('admin.opening-hours.index');
	}	
}
