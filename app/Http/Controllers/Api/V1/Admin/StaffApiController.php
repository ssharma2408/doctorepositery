<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Resources\Admin\StaffResource;
use App\Models\Staff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffApiController extends Controller
{
    public function index(Request $request)
    {
        //abort_if(Gate::denies('staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaffResource(Staff::where('clinic_id', $request->clinic_id)->with(['clinic'])->get());
    }

    public function store(StoreStaffRequest $request)
    {
        $staff = Staff::create($request->all());

        return (new StaffResource($staff))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Staff $staff)
    {
        //abort_if(Gate::denies('staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaffResource($staff->load(['clinic']));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $staff->update($request->all());

        return (new StaffResource($staff))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Staff $staff)
    {
        //abort_if(Gate::denies('staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function profile($id)
	{
		return new StaffResource(Staff::where('id', $id)->first());
	}
	
	public function update_profile(Request $request)
	{
		$update_arr = [
					   'name' =>  $request->name,
					   'email' =>  $request->email,
					   'mobile_number' =>  $request->mobile_number,					   
					];
		if(isset($request->password)){
			$update_arr['password'] = $request->password;
		}
		
		Staff::where('id', $request->id)
				   ->update($update_arr);
		return true;
	}
}
