<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RateChart;
use App\Models\Zone;
use App\Models\Country;

class ZoneScheme extends Model
{
    use HasFactory;

    public function rate_charts()
    {
        return $this->hasMany(RateChart::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function countries()
    {
        return $this->hasManyThrough(
                                        Country::class,
                                        Zone::class,
                                    );
    }


}
