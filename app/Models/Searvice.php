<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Searvice extends Model
{
    use HasFactory;

    use LogsActivity;

    protected $fillable = ['service_name', 'platforms','position'];

    public function platforms(){
        return $this->hasMany(Platform::class, 'platform_id');
    }

    public function packages()
    {
        return $this->hasMany(ServicePackage::class, 'service_id');
    }
    public function features()
    {
        return $this->hasMany(ServiceFeature::class, 'service_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Service');
    }

}
