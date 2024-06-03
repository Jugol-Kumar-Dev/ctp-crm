<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    protected $dates = ['payment_date'];

    public function receivedBy(){
        return $this->belongsTo(User::class, 'received_by');
    }

    public function paymentBy(){
        return $this->belongsTo(Client::class, 'payment_by');
    }

    public function method(){
        return $this->belongsTo(Method::class, 'method_id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('Transaction');
    }

}
