<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timing extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'timings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SHIFT_RADIO = [
        '1' => 'One Shift',
        '2' => 'Two Shift',
    ];

    public const DAY_SELECT = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
    ];

    protected $fillable = [
        'user_id',
        'day',
        'shift',
        'form',
        'to',
        'before_from',
        'before_to',
        'after_from',
        'after_to',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
