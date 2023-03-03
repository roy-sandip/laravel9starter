<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RateType;
use App\Models\Zone;

class Rate extends Model
{
    use HasFactory;
    protected $casts = [
        'type' => RateType::class,
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }


}
