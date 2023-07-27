<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes, HasFactory;
	
	public $table = 'family';
	
	protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
	
	protected $fillable = [
        'owner_id', 'address'
    ];
	
	 protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
