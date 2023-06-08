<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin\BaseController as BaseController;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Domain;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

use Hash;

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
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
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
				}			
				
				if( ! $clinic['status']){
					return $this->sendError('Package error', ['error'=>'No package is active']);
				}
				
				$domain = Domain::where('id', $clinic->domain_id)->first();
				
				if($domain->name != $request->domain){
					return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
				}

				$success['token'] =  $user->createToken('MyApp')->plainTextToken; 
				$success['name'] =  $user->name;
				$success['role'] =  $user_role;
				$success['postfix'] =  $clinic->prefix;

				return $this->sendResponse($success, 'User login successfully.');
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
}
