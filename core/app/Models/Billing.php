<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shipment;
use App\Models\Agent;
use App\Models\Invoice;

class Billing extends Model
{
    use HasFactory;
    const PENDING   = 'PENDING';
    const PAID      = 'PAID';
    const DUE       = 'DUE';

     public static function boot() 
    {
            parent::boot();
      
            /**
             * Write code on Method
             *
             * @return response()
             */
            static::creating(function($item) {            
                $item->calculateDue();
            });
            static::updating(function($item) {            
                $item->calculateDue();
            });
      
            
    }

    protected function calculateDue()
    {
        $this->due = $this->bill - $this->paid;
        $this->setStatus();
        return $this;
    }
    
    protected function setStatus()
    {
        if(!isset($this->bill))
        {
            $this->status = self::PENDING;return $this;
        }
        if($this->bill > $this->paid || $this->due > 0)
        {
            $this->status = self::DUE; return $this;
        }

        $this->status = self::PAID; return $this;
    }


    public function getStatusList()
    {
        return [self::PENDING, self::PAID, self::DUE];
    }

    public function getDue()
    {
        return $this->due;
    }
    public function getBill()
    {
        return $this->bill;
    }

    public function getPaid()
    {
        return $this->paid;
    }


    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

   

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->withDefault();
    }



}
