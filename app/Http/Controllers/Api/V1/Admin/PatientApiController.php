<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\Admin\PatientResource;
use App\Models\Patient;
use App\Models\ShortLink;
use App\Models\FamilyLog;
use App\Models\Family;
use Exception;
use Twilio\Rest\Client;
use Gate;
use DB;
use Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientApiController extends Controller
{
    public function index(Request $request)
    {
        $members = Patient::where('family_id', $request->family_id)->get();
		$owner_id = Family::where('id', $request->family_id)->first()->owner_id;
		
		return new PatientResource(['members'=>$members, 'owner_id'=> $owner_id]);       
    }

    public function store(Request $request)
    {
        if(isset($request->mobile_number)){
			$member = Patient::create($request->all());
		}else{			
			$patient_detail = $request->all();
			$patient_detail['mobile_number'] = $request->user_mobile_number."-".rand(pow(10, 3-1), pow(10, 3)-1);
			$member = Patient::create($patient_detail);
		}

		return (new PatientResource($member))
				->response()
				->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Patient $patient)
    {
        return new PatientResource($patient);
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        
		$patient->update($request->all());

        return (new PatientResource($patient))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Patient $patient)
    {
        $history = FamilyLog::where('patient_id', $patient->id)->orderBy('id', 'DESC')->first();
		
		if(!empty($history)){
			$change_log = [];
			$change_log['patient_id'] = $patient->id;
			$change_log['old_family_id'] = $history->new_family_id;
			$change_log['new_family_id'] = $history->old_family_id;
			
			FamilyLog::create($change_log);
			
			Patient::where('id', $patient->id)
			   ->update([
				   'family_id' =>  $history->old_family_id
				]);
			if( ! $patient->is_dependent){
				$dependents = Patient::where('added_by', $patient->id)->get();
				if(!empty($dependents)){
					
					$data = [];
					$memmer_ids = [];
					
					foreach($dependents as $dependent){
						$change_log = [];
						$change_log['patient_id'] = $dependent->id;
						$change_log['old_family_id'] = $history->new_family_id;
						$change_log['new_family_id'] = $history->old_family_id;
						
						$data[] = $change_log;
					
						$memmer_ids[] = $dependent->id;
					}
					
					if(!empty($data)){
						FamilyLog::insert($data);
					}
					
					Patient::whereIn('id', $memmer_ids)
						   ->update([
							   'family_id' =>  $history->old_family_id
							]);
					
				}
			}
			return new PatientResource(["family_id"=>$history->old_family_id]);
		}else{
			$patient->delete();
			return response(null, Response::HTTP_NO_CONTENT);
		}		

        
    }
	
	public function get_patients(Request $request)
	{
		$patients = DB::select('SELECT p.id, p.name, ph.visit_date, ph.id as history_id
								FROM patients p 
								INNER JOIN patient_histories ph on p.id=ph.patient_id 								
								WHERE ph.doctor_id = ?
								ORDER BY p.id
								', [$request->doctor_id]);
		
		return new PatientResource($patients);
	}

	public function sendsms(Request $request)
	{
		$patient = Patient::where('mobile_number', $request->mobile_number)->first();
		
		$url = "f_id=".$request->family_id."&p_id=".$patient->id;
		
		$input['link'] = $url;
        $input['code'] = Str::random(6);
   
        $short_link = $request->site_url.'c/'.ShortLink::create($input)->code;
		
		$message = $request->user_name."(".$request->user_mobile_number.") wants to add you in his family. Cick on ".$short_link." to accept invitation.";
		
		$patient = new Patient();
		
		$status = $patient->sendSMS($request->mobile_number, $message); 
		
		//if($status){
			return new PatientResource(["status"=>1, "message"=>$message]);
		/* }else{
			return new PatientResource(["status"=>0]);
		} */
		
		/* try {
  
            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");			
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($request->mobile_number, [
                'from' => $twilio_number, 
                'body' => $message]);
   
            info('SMS Sent Successfully.');
			return new PatientResource(["status"=>1, "message"=>$message]);
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
			return new PatientResource(["status"=>0]);
        } */
	}
	
		/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_family($code)
    {
        $find = ShortLink::where('code', $code)->first();
		
		if(!empty($find)){
   
			$details = explode("&", $find->link);
			
			$new_family_id = explode("=", $details[0])[1];
			$patient_id = explode("=", $details[1])[1];
			
			$patient = Patient::where('id', $patient_id)->first();
			
			$old_family_id = $patient->family_id;
			
			if($new_family_id != $old_family_id){
				
				$members = Patient::where(['family_id' => $old_family_id, 'is_dependent' => 1])->get();
				
				$data = [];
				$memmer_ids = [];
				
				$change_log = [];
				$change_log['patient_id'] = $patient_id;
				$change_log['old_family_id'] = $old_family_id;
				$change_log['new_family_id'] = $new_family_id;
					
				FamilyLog::create($change_log);
				
				Patient::where('id', $patient_id)
				   ->update([
					   'family_id' =>  $new_family_id
					]);
				
				foreach($members as $member){
				
					$change_log = [];
					$change_log['patient_id'] = $member->id;
					$change_log['old_family_id'] = $old_family_id;
					$change_log['new_family_id'] = $new_family_id;
					
					$data[] = $change_log;
					
					$memmer_ids[] = $member->id;
				}
				
				if(!empty($data)){
					FamilyLog::insert($data);
				}
				
				Patient::whereIn('id', $memmer_ids)
					   ->update([
						   'family_id' =>  $new_family_id
						]);
						
				return new PatientResource(["status"=>1]);
				
			}else{
				return new PatientResource(["status"=>0]);
			}	
			
		}		
    }
	
	public function search_patients($clinic_id, $doc_id, $search_term)
	{
		
		$condition = "";
		
		if($search_term != 'all'){
			$condition = 'AND (p.name LIKE "%'.$search_term.'%" OR p.mobile_number LIKE "%'.$search_term.'%")';
		}
		
		
		$patients = DB::select('SELECT p.id, p.name, p.mobile_number, ph.visit_date, ph.id as history_id
								FROM patients p 
								INNER JOIN patient_histories ph ON p.id=ph.patient_id
								WHERE p.clinic_id = ? 
								AND ph.doctor_id =? 
								'.$condition.'
								ORDER BY p.id;
								', [$clinic_id, $doc_id]);
		
		return new PatientResource($patients);				
	}
	
	public function profile($id)
	{
		return new PatientResource(Patient::where('id', $id)->first());
	}
	
	public function update_profile(Request $request)
	{
		$update_arr = [
					   'name' =>  $request->name,
					   'gender' =>  $request->gender,
					   'mobile_number' =>  $request->mobile_number,					   
					   'dob' =>  $request->dob,					   
					];		
		
		Patient::where('id', $request->id)
				   ->update($update_arr);
		return true;
	}
}
