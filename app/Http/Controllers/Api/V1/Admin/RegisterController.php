<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin\BaseController as BaseController;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Domain;
use App\Models\Staff;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientOtp;
use Illuminate\Support\Facades\Auth;
use App\Models\Family;
use Validator;
use Illuminate\Http\JsonResponse;

use Hash;
use DB;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {        
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile_number' => 'required',
            'gender' => 'required',
        ]);
		
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
		$family_id = Family::create()->id;		
   
        $input = $request->all();
		
		$input['family_id'] = $family_id;
		
        //$input['password'] = bcrypt($input['password']);
        $patient = Patient::create($input);
        $success['token'] =  $patient->createToken('MyApp')->plainTextToken;
        $success['name'] =  $patient->name;
		$success['role'] =  'Patient';
		$success['id'] =  $patient->id;
		$success['family_id'] =  $patient->family_id;
   
        return $this->sendResponse($success, 'Patient register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        
		if( ! isset($request->clinic_id)){
			return $this->sendError('Configuartion Error', ['error'=>'Please pass Clinic ID']);
		}
		
		if( ! isset($request->domain)){
			return $this->sendError('Configuartion Error', ['error'=>'Please pass domain']);
		}

		//check if clinic admin/doctor or staff
		if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
			if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

				$user = Auth::user();
				
				$user_role = $user->roles->toArray()[0]['title'];
				
				if($user_role == "Clinic Admin"){
					$clinic = Clinic::where('clinic_admin_id', $user->id)->first();
				}else{
					$clinic = Clinic::where('id', $request->clinic_id)->first();
					
					$doctor_arr = [];
					foreach($clinic->doctors as $doctor){
						$doctor_arr[] = $doctor->id;
					}
					
					if( ! in_array($user->id, $doctor_arr)){
						return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
					}
				}			
				
				if( ! $clinic['status']){
					return $this->sendError('Package error', ['error'=>'No package is active']);
				}
				
				$domain = Domain::where('id', $clinic->domain_id)->first();
				
				if($domain->name != $request->domain){
					return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
				}				
				
				if($clinic->id == $request->clinic_id){
					$success['token'] =  $user->createToken('MyApp')->plainTextToken; 
					$success['name'] =  $user->name;
					$success['user_id'] =  $user->id;
					$success['role'] =  $user_role;
					$success['prefix'] =  $clinic->prefix;

					return $this->sendResponse($success, 'User login successfully.');
				}else{
					return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
				}
			} 
			else{
				return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
			} 
		}else{
			$staff = Staff::where('username', $request->email)->first();
			
			if(!empty($staff) && Hash::check($request->password, $staff->password)){
				
				if($staff->clinic_id == $request->clinic_id){
					 
					$success['token'] =  $staff->createToken('MyApp')->plainTextToken;
					$success['name'] =  $staff->name;
					$success['username'] =  $staff->username;
					$success['role'] =  'Staff';
					return $this->sendResponse($success, 'User login successfully.');
				}else{
					return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
				}
			}else{
				return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
			}
		}	
		
    }
	
	/**
     * Patient Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request): JsonResponse
    {
		if( ! isset($request->clinic_id)){
			return $this->sendError('Configuartion Error', ['error'=>'Please pass Clinic ID']);
		}
		
		if( ! isset($request->domain)){
			return $this->sendError('Configuartion Error', ['error'=>'Please pass domain']);
		}
		
		$request->validate([
            'mobile_number' => 'required|exists:patients,mobile_number'
        ]);
		
        /* Generate An OTP */
        $patientOtp = $this->generateOtp($request->clinic_id, $request->mobile_number);
        $patientOtp->sendSMS($request->mobile_number);  
        
		return $this->sendResponse(['patient_id' => $patientOtp->patient_id], 'OTP has been sent on Your Mobile Number.');
		
	}
	
	 /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateOtp($clinic_id, $mobile_no)
    {
        $patient = Patient::where('mobile_number', $mobile_no)->first();
		
		if($patient->clinic_id != $clinic_id){
			return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this clinic']);
		}
  
        /* patient Does not Have Any Existing OTP */
        $patientOtp = PatientOtp::where('patient_id', $patient->id)->latest()->first();
  
        $now = now();
  
        if($patientOtp && $now->isBefore($patientOtp->expire_at)){
            return $patientOtp;
        }
  
        /* Create a New OTP */
        return PatientOtp::create([
            'patient_id' => $patient->id,
            //'otp' => rand(123456, 999999),
            'otp' => '123456',
            'expire_at' => $now->addMinutes(10)
        ]);
    }
	
	/**
     * Write code on Method
     *
     * @return response()
     */
    public function loginWithOtp(Request $request)
    {
        /* Validation */
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'otp' => 'required'
        ]);  
  
        /* Validation Logic */
        $patientOtp   = PatientOtp::where('patient_id', $request->patient_id)->where('otp', $request->otp)->first();
  
        $now = now();
        if (!$patientOtp) {
            
			return $this->sendError('error', ['error'=>'Your OTP is not correct']);
        }
		/* else if($patientOtp && $now->isAfter($patientOtp->expire_at)){            
			return $this->sendError('error', ['error'=>'Your OTP has been expired']);
        } */
    
        $patient = Patient::whereId($request->patient_id)->first();
  
        if($patient){
              
            $patientOtp->update([
                'expire_at' => now()
            ]);
			$patient->token =  $patient->createToken('MyApp')->plainTextToken;
			$patient->role = "Patient";
            //Auth::login($patient);
  
            return $this->sendResponse($patient, 'Patient Logged in successfully.');
        }
		return $this->sendError('error', ['error'=>'Your Otp is not correct']);
    }
}
