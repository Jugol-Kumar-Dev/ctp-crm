<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Note extends Model
{
    use HasFactory, LogsActivity;


    protected $guarded = ['id'];

    public function noteCategory(){
        return $this->belongsTo(NoteCategory::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Notes');
    }
}
