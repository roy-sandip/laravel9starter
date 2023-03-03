<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ShipmentPermissionScope;
use App\Models\Address;
use App\Models\Agent;
use App\Models\Service;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Shipment extends Model
{
    use HasFactory;
     const AWB_SERIAL = 32000000;
    

    protected $casts = [
        'booking_date'  => 'datetime',
        'updates'       => 'object',
    ];

     protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ShipmentPermissionScope);
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->booking_date
        );
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Address::class, 'shipper_id')->withDefault();
    }

    public function receiver()
    {
        return $this->belongsTo(Address::class, 'receiver_id')->withDefault();
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }


    

    public function addUpdate($status, $location, Carbon $timestamp)
    {
        $update =[
            'activity'  => $status,
            'location'  => $location,
            'time'      => $timestamp->format('h:ia'),
            'date'      => $timestamp->format('d M, Y'),
            'user_id'   => auth()->user()->id ?? null,
            'ip'        => request()->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        
        $this->updates = is_array($this->updates) ? array_merge($this->updates, [$update]) : [$update];
        return $this;
    }


    public function editUpdate()
    {

    }

    
    
    public function setAWB()
    {
        $this->awb = self::AWB_SERIAL + $this->id;
        return $this;
    }

  
    
}
