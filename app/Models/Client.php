<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static findOrFail(int $id)
 */
class Client extends Model
{


    use LogsActivity;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'clients';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $dates = ['follow_up'];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'secondary_email',
        'secondary_phone',
        'note',
        'follow_up',
        'status',
        'is_client',
        'created_by',
        'updated_by',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot() {
        parent::boot();
        self::deleting(function($client) {
            $client->quotations()->each(function($quotation) {
                $quotation->delete();
             });
        });
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : '/images/avatar.png',
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function quotations()
    {
        return $this->hasMany('App\Models\Quotation');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'client_user', 'client_id', 'user_id');
    }

    public function projects(){
        return $this->belongsToMany(Project::class, 'client_project');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'payment_by');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName($this->is_client == 0 ?  'Lead' : 'Client');
    }
}
