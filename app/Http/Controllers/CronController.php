<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortLink;

use DB;

class CronController extends Controller
{
    public function cron_clear_tokens()
	{
		DB::table('tokens')->delete();
		DB::table('patient_otps')->delete();
		
		echo "All tokens cleared successfully!";
	}
}
