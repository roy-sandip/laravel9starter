<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZoneScheme;
use App\Models\Company;
use App\Models\Zone;
use App\Models\Rate;

class RateChart extends Model
{
    use HasFactory;
    protected $casts = [
            'active'=> 'boolean',
    ];

    public function zone_scheme()
    {
        return $this->belongsTo(ZoneScheme::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function zones()
    {
        return $this->zone_scheme->zones;
    }




}
