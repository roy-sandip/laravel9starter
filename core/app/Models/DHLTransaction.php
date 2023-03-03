<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\MediaPivot;
use App\Models\DHLBill;


class DHLTransaction extends Model
{
    use HasFactory;
    protected $casts = [
        'datetime' => 'datetime',
    ];


    public function attachment()
    {
        return $this->hasOneThrough(
                                    Media::class, 
                                    MediaPivot::class, 
                                    'model_id',  //media foreign key
                                    'id',   //media primary key
                                     null, 
                                    'media_id' //pivot foreign key for media
                                )->where('media_pivots.model', get_class($this));
    }

    public function getAmount($format = false)
    {
        return $format ? number_format($this->amount, $format) : $this->amount;
    }

    
}
