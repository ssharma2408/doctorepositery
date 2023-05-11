<?php

namespace App\Models;

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

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'address',
        'package_ids_id',
        'clinic_adminid_id',
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

    public function clinicIdsDoctors()
    {
        return $this->hasMany(Doctor::class, 'clinic_ids_id', 'id');
    }

    public function clinicIdsStaffs()
    {
        return $this->hasMany(Staff::class, 'clinic_ids_id', 'id');
    }

    public function package_ids()
    {
        return $this->belongsTo(Package::class, 'package_ids_id');
    }

    public function clinic_adminid()
    {
        return $this->belongsTo(User::class, 'clinic_adminid_id');
    }
}
