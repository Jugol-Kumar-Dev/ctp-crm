<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @method static findOrFail(int $id)
 */
class Project extends Model
{

    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'projects';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'user_id',
        'client_id',
        'invoice_id',
        'date',
        'start',
        'end',
        'description',
        'credential',
        'status',
        'backup',
        'progress',
        'files',
    ];

//    protected $hidden = ['credential'];
    protected $dates = [
        'date',
        'start',
        'end',
    ];

//    protected function files(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => $value ? Storage::url($value) : '/images/creativeTechPark.png'
////            set: fn ($value) => $value->store('image', 'public'),
//        );
//    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->withTimestamps();
    }

    public function clients(){
        return $this->belongsToMany(Client::class, 'client_project', 'project_id', 'client_id')->withTimestamps();
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Project');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
