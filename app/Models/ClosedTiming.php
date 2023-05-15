<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClosedTiming extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'closed_timings';

    public const FULL_PARTIAL_RADIO = [
        '1' => 'Full Day',
        '2' => 'Partial',
    ];

    protected $dates = [
        'closed_on',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'full_partial',
        'closed_on',
        'from',
        'to',
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

    public function getClosedOnAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setClosedOnAttribute($value)
    {
        $this->attributes['closed_on'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
