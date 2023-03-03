<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rate;
use App\Models\ZoneScheme;
use App\Models\Country;
use App\Models\ZoneCountry;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = [
                            'name', 
                        ];
                        
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function countries()
    {
        return $this->hasManyThrough(
                                    Country::class,
                                    ZoneCountry::class,
                                    'zone_id',
                                    'id',
                                    'id',
                                    'country_id'
                                );  
    }

    public function scheme()
    {
        return $this->belongsTo(ZoneScheme::class, 'zone_scheme_id');
    }
}
