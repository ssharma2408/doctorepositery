<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin\BaseController as BaseController;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

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

		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();

			$clinic = Clinic::where('clinic_admin_id', $user->id)->first();
			
			if( ! $clinic['status']){
				return $this->sendError('Package error', ['error'=>'No package is active']);
			}
			
			$domain = Domain::where('id', $clinic->domain_id)->first();
			
			if($domain->name != $request->domain){
				return $this->sendError('Authorisation error', ['error'=>'You are not authorised for this doamin']);
			}

            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            $success['role'] =  $user->roles->toArray()[0]['title'];
            $success['postfix'] =  $clinic->prefix;

            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
