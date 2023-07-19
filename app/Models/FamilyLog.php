<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyLog extends Model
{
    use SoftDeletes, HasFactory;
	
	public $table = 'family_log';
	
	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	 protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
	
	protected $fillable = [
        'patient_id', 'old_family_id', 'new_family_id'
    ];
}
