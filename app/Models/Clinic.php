<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Clinic extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'clinics';

    public const STATUS_SELECT = [
        '0' => 'Disabled',
        '1' => 'Enabled',
        '2' => 'Expired',
    ];

    protected $dates = [
        'package_start_date',
        'package_end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'prefix',
        'address',
        'package_id',
        'package_start_date',
        'package_end_date',
        'clinic_admin_id',
        'domain_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function getPackageStartDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setPackageStartDateAttribute($value)
    {
        $this->attributes['package_start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getPackageEndDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setPackageEndDateAttribute($value)
    {
        $this->attributes['package_end_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function clinic_admin()
    {
        return $this->belongsTo(User::class, 'clinic_admin_id');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }
}
