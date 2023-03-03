<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\MediaPivot;
use App\Models\DHLTransaction;

class DHLBill extends Model
{
    use HasFactory;
    protected $casts = [
        'bill_date' => 'datetime',
    ];


    public function attachments()
    {
        return $this->hasManyThrough(
                                    Media::class, 
                                    MediaPivot::class, 
                                    'model_id',  //media foreign key
                                    'id',   //media primary key
                                     null, 
                                    'media_id' //pivot foreign key for media
                                )->where('media_pivots.model', get_class($this));
    }


    public function transactions()
    {
        return $this->hasMany(DHLTransaction::class,'dhlbill_id');
    }


    public function calculateBalance()
    {
        $totalPaid = $this->transactions->sum('amount');
        $this->balance = $this->total_bill - $totalPaid;
        return $this;
    }


    //get attributes
    public function getBill($format = false)
    {
        return ($format) ? number_format($this->total_bill, $format) : $this->total_bill;
    }
    public function getBalance($format = false)
    {
        return ($format) ? number_format($this->balance, $format) : $this->balance;
    }

    public function getPaid($format = false)
    {
        $paid = isset($this->transactions) 
                ? $this->transactions->sum('amount')
                : $this->transactions()->sum('amount')->first();

        return ($format) ? number_format($paid, $format) : $paid;
    }


    public function lock()
    {
        $this->readonly = true;
        return $this;
    }

    public function unlock()
    {
        $this->readonly = false;
        return $this;
    }

    public function locked()
    {
        return $this->readonly;
    }


}
