<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneCountry extends Model
{
    use HasFactory;

    protected $table = 'zone_country';
    protected $fillable = [
                    'zone_scheme_id',
                    'country_id',
                    'rate_chart_id',
    ];

    
}
