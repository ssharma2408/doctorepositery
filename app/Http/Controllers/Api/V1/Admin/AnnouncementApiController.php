<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

use App\Http\Resources\Admin\AnnouncementResource;

class AnnouncementApiController extends Controller
{
    public function announcements($clinic_id)
	{
		return new AnnouncementResource(Announcement::where(['clinic_id'=>$clinic_id, 'status'=>1])->get());
	}
}
