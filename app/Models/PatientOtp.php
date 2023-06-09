<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Twilio\Rest\Client;

class PatientOtp extends Model
{
    use HasFactory;
	
	protected $fillable = ['patient_id', 'otp', 'expire_at'];
	
	/**
     * Write code on Method
     *
     * @return response()
     */
    public function sendSMS($receiverNumber)
    {
        $message = "Login OTP is ".$this->otp;
    
        try {
  
            $account_sid = $_ENV["TWILIO_SID"];
            $auth_token = $_ENV["TWILIO_TOKEN"];
            $twilio_number = $_ENV["TWILIO_FROM"];
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
   
            info('SMS Sent Successfully.');
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }
}
