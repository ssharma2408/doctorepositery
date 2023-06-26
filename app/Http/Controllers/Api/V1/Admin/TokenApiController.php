<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\UpdateTokenRequest;
use App\Http\Resources\Admin\TokenResource;
use App\Models\Token;
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
        $init_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id])->orderBy('token_number', 'DESC')->first();
		
		$token_arr = $request->all();
		
		$flag = 1;
		
		// Start Token
		if(empty($init_token)){
			$token_arr['token_number'] = 1;
			$token_arr['estimated_time'] = 00.00;
			$token_arr['current_token'] = 1;
		}else{
			//check patient already has token
			$exist_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'patient_id'=>$request->patient_id])->get();
			
			$current_token = Token::where(['clinic_id'=>$request->clinic_id, 'doctor_id'=>$request->doctor_id, 'estimated_time'=>0])->first();			
			
			if(count($exist_token)){
				$flag = 0;
				$token_arr = $exist_token[0];
			}else{				
				$token_arr['token_number'] = $init_token['token_number'] + 1;
				$token_arr['estimated_time'] = $init_token['estimated_time'] + 10;				
			}
			$token_arr['current_token'] = $current_token->token_number;
		}	

		if($flag){
			$token = Token::create($token_arr);
		}else{
			$token = $token_arr;
		}		
        
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
		$patients = DB::select('SELECT p.id, p.name, t.token_number, t.status
								FROM patients p 
								INNER JOIN tokens t on p.id=t.patient_id
                                WHERE t.status <> 0
								AND t.clinic_id = ?
								AND t.doctor_id = ?
                                ORDER BY t.token_number;', [$request->clinic_id, $request->doctor_id]);
		return (new TokenResource($patients))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
	}
	
	public function update_token(Request $request)
	{
		$success = true;
		
		Token::where('patient_id', $request->patient_id)
			   ->update([
				   'status' =>  $request->status
				]);
		
		Token::where(['doctor_id'=> $request->doctor_id, 'clinic_id'=> $request->clinic_id])->where('status','<>','0')
				->update([
				   'estimated_time'=> DB::raw('estimated_time-10') 
				]);
		
		
		return $success;		
	}
}
