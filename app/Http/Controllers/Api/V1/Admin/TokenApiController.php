<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\UpdateTokenRequest;
use App\Http\Resources\Admin\TokenResource;
use App\Models\Token;
use App\Models\Timing;
use App\Models\Patient;
use App\Models\Family;
use App\Models\PatientHistory;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('token_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TokenResource(Token::with(['clinic', 'doctor', 'patient'])->get());
    }

    public function store(StoreTokenRequest $request)
    {
        $init_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'timing_id'=>$request->slot_id])->orderBy('token_number', 'DESC')->first();
		
		$token_arr = $request->all();		
		
		$flag = 1;
		
		// Start Token
		if(empty($init_token)){
			$token_arr['token_number'] = 1;
			$token_arr['estimated_time'] = 00.00;
			$token_arr['current_token'] = 1;
			$token_arr['timing_id'] = $request->slot_id;
			$token_arr['is_online'] = 1;
			$token_arr['mobile_number'] = "";
		}else{
			//check patient already has token
			$exist_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'patient_id'=>$request->patient_id, 'timing_id'=>$request->slot_id])->get();
			
			$current_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'timing_id'=>$request->slot_id, 'estimated_time'=>0])->first();
			
			if(count($exist_token)){
				$flag = 0;
				$token_arr = $exist_token[0];
			}else{
				$timing = Timing::where('id', $request->slot_id)->first();
				$token_arr['token_number'] = $init_token['token_number'] + 1;
				$token_arr['estimated_time'] = $init_token['estimated_time'] + $timing->time_per_token;
				$token_arr['timing_id'] = $request->slot_id;
				$token_arr['is_online'] = 1;
				$token_arr['mobile_number'] = "";
			}
			$token_arr['current_token'] = $current_token->token_number;
		}	

		if($flag){
			$token = Token::create($token_arr);
			$token['current_token'] = $token_arr['current_token'];
		}else{
			$token = $token_arr;
		}		
		$timing_start = Timing::where('id', $request->slot_id)->first();

		$token['current_token'] = (!$timing_start->is_started) ? "Not Started" : $token['current_token'];

		return (new TokenResource($token))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Token $token)
    {
        abort_if(Gate::denies('token_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TokenResource($token->load(['clinic', 'doctor', 'patient']));
    }

    public function update(UpdateTokenRequest $request, Token $token)
    {
        $token->update($request->all());

        return (new TokenResource($token))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Token $token)
    {
        abort_if(Gate::denies('token_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $token->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_patients(Request $request)
	{
		$patients = DB::select('SELECT p.id, p.name, t.token_number, t.status, t.timing_id, tim.start_hour, tim.end_hour, t.is_online, t.id as token_id, tim.is_started
								FROM tokens t 
								LEFT JOIN patients p ON p.id=t.patient_id
								INNER JOIN timings tim on tim.id=t.timing_id								
                                WHERE t.status <> 0
								AND t.clinic_id = ?
								AND t.doctor_id = ?
                                ORDER BY tim.start_hour, t.token_number;', [$request->clinic_id, $request->doctor_id]);
		return (new TokenResource($patients))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
	
	public function update_token(Request $request)
	{
		$success = true;
		
		if($request->status == 0 || $request->status == 2){
			$timing = Timing::where('id', $request->slot_id)->first();
			
			$time_per_token = $timing->time_per_token * 60;			
			
			$operator = "-";
			
			// Update estimated time based on Actual time taken for Patient
			if($time_per_token >= $request->time_taken){
				$time_per_token = $time_per_token + ($time_per_token - $request->time_taken);				
			}else{
				$time_per_token = $time_per_token - ($request->time_taken - $time_per_token);
			}

			if($request->is_online){
				if($request->status == 0){
					$history_arr = [
										'visit_date' => date("Y-m-d H:i:s"),
										'prescription' => $request->prescription,
										'comment' => $request->comment,
										'patient_id' => $request->patient_id,
										'doctor_id' => $request->doctor_id,
										'next_visit_date' => (!empty($request->next_visit_date)) ? $request->next_visit_date : null,
									];
					
					PatientHistory::insert($history_arr);
				}
				$type = 'patient_id';
			}else{
				$type = 'id';
			}
			
			Token::where($type, $request->patient_id)
				   ->update([
					   'status' =>  $request->status
					]);

			DB::statement(
						
						"UPDATE tokens SET estimated_time =
						(
							CASE
								WHEN (((estimated_time".$operator.ceil(($time_per_token / 60) % 60).") > 0) AND ((estimated_time".$operator.ceil(($time_per_token / 60) % 60).") > ".$timing->time_per_token."))
								THEN estimated_time".$operator.ceil(($time_per_token / 60) % 60)."
								ELSE 0
							END
						)
						WHERE doctor_id=".$request->doctor_id."
						AND clinic_id=".$request->clinic_id."
						AND timing_id=".$request->slot_id."
						AND estimated_time > 0
						AND status IN (1,2)"							
			);
		}
		
		
		return $success;
	}
	
	public function refresh_status(Request $request){
		

		$exist_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'patient_id'=>$request->patient_id, 'timing_id'=>$request->slot_id])->get();
			
		$current_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'estimated_time'=>0, 'status'=>1])->orderBy('id', 'ASC')->first();
		
		$token_arr = $exist_token[0];
		
		$timing_start = Timing::where('id', $request->slot_id)->first();		
		
		$token_arr['current_token'] = (!$timing_start->is_started) ? "Not Started" : $current_token->token_number;
		
		$token_arr['estimated_time'] = intdiv($token_arr['estimated_time'], 60).':'. ($token_arr['estimated_time'] % 60);
		
		return (new TokenResource($token_arr))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
	
	public function check_status(Request $request)
	{
		$exist_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'timing_id'=>$request->slot_id, 'patient_id'=>$request->patient_id])->get();

		$exist_token['estimated_time'] = intdiv($exist_token['estimated_time'], 60).':'. ($exist_token['estimated_time'] % 60);

		return (new TokenResource($exist_token))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
	
	public function refresh_token($slot_id)
	{

		$token = Token::where(['timing_id'=>$slot_id])->orderBy('id', 'DESC')->first();	
		
		return (new TokenResource($token))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
	
	public function create_token(Request $request){
		
		$patient  = Patient::where('mobile_number', $request->mobile_number)->first();
		
		if(empty($patient) || isset($request->member)){
			
			$token_arr = [];		

			if(isset($request->member)){
				$token = Token::where(['patient_id'=>$request->member, 'timing_id'=>$request->slot_id])->first();
			}else{
				$token = Token::where(['mobile_number'=>$request->mobile_number, 'timing_id'=>$request->slot_id])->first();
			}

			if(empty($token)){
			
				$current_token = Token::where(['timing_id'=>$request->slot_id])->orderBy('id', 'DESC')->first();
					
				if(empty($current_token)){
					$token_arr['token_number'] = 1;
					$token_arr['estimated_time'] = 0;				
				
				}else{
					$time_per_token = Timing::where('id', $request->slot_id)->first()->time_per_token;				
					
					$token_arr['token_number'] = $current_token->token_number + 1;
					$token_arr['estimated_time'] = $current_token->estimated_time + $time_per_token;
				}

				$token_arr['clinic_id'] = $request->clinic_id;
				$token_arr['doctor_id'] = $request->doctor_id;
				$token_arr['patient_id'] = isset($request->member) ? $request->member : 0;
				$token_arr['status'] = 1;
				$token_arr['timing_id'] = $request->slot_id;
				$token_arr['is_online'] = isset($request->member) ? 1 : 0;
				$token_arr['mobile_number'] = isset($request->member) ? "" : $request->mobile_number;

				$token = Token::create($token_arr);
				
				return (new TokenResource($token))
					->response()
					->setStatusCode(Response::HTTP_ACCEPTED);
				
			}else{
				return (new TokenResource(['msg'=> 'processed']))
					->response()
					->setStatusCode(Response::HTTP_ACCEPTED);
			}
			
		}else{
			
			$patients = Patient::where('family_id', $patient->family_id)->get();
			
			return (new TokenResource(['msg'=> 'Patient already has account.', 'members'=>$patients]))
					->response()
					->setStatusCode(Response::HTTP_ACCEPTED);
		}
		
	}
	
	public function work_status($slot_id, $status)
	{
		Timing::where('id', $slot_id)
				   ->update([
					   'is_started' =>  $status
					]);
		return true;
	}
}
