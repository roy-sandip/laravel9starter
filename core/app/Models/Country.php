<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Zone;
use App\Models\ZoneCountry;
use App\Models\Rate;


class Country extends Model
{
    use HasFactory;


    
    public function zone()
    {
        return $this->hasOneThrough(
                                        Zone::class,
                                        ZoneCountry::class,
                                        'country_id',
                                        'id',
                                        'id',
                                        'zone_id'
                                    );
    }

    public function zones()
    {
        return $this->hasManyThrough(
                                        Zone::class,
                                        ZoneCountry::class,
                                        'country_id',
                                        'id',
                                        'id',
                                        'zone_id'
                                    );
    }


}
