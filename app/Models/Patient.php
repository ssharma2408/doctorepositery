<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

use Exception;
use Twilio\Rest\Client;

class Patient extends Model
{
    use HasApiTokens, SoftDeletes, HasFactory;

    public $table = 'patients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'mobile_number',
        'gender',
        'dob',
        'is_dependent',
        'added_by',
        'clinic_id',
        'family_id',       
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
	
	/**
     * Write code on Method
     *
     * @return response()
     */
    public function sendSMS($receiverNumber, $message)
    {		
		try {

            $account_sid = \Config::get("values.twillo_sid");
            $auth_token = \Config::get("values.twillo_token");
            $twilio_number = \Config::get("values.twillo_from");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
   
            info('SMS Sent Successfully.');
			return true;
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
			return false;
        }
    }
}
