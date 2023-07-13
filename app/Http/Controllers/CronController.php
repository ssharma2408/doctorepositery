<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class CronController extends Controller
{
    public function cron_clear_tokens()
	{
		DB::table('tokens')->delete();
		
		echo "All tokens cleared successfully!";
	}
}
