<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array)
 * @method static whereIn(mixed $json_decode)
 * @method static findOrFail(int|string|null $id)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use  HasRoles, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'address',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : '/images/avatar.png',
        );
    }



    public function quotations()
    {
        return $this->hasMany('App\Models\Quotation', 'created_by');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Models\Client', 'client_user');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'user_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user','project_id', 'user_id')->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'received_by');
    }

    public function expanses()
    {
        return $this->hasMany(Expanse::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('User')
            ->logOnly(['name', 'email', 'text']);
    }


}
