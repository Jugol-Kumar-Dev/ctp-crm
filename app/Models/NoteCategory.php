<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static findOrFail($id)
 */
class NoteCategory extends Model
{
    use HasFactory, LogsActivity;


    protected $guarded = ['id'];

    public function notes(){
        return $this->hasMany(Note::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Notes Category');
    }
}
