<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Billing;

class Agent extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'company', 'phone', 'email', 'address'];
    const MAIN_BRANCH_ID = 1;

    public function is_MainBranch()
    {
        return $this->id === self::MAIN_BRANCH_ID;
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    public function billings()
    {
        return  $this->hasManyThrough(Billing::class, Shipment::class);
    }

    

    public function lastInvoice()
    {
        return $this->hasOne(Invoice::class)->latest('id')->withDefault();
    }





}
