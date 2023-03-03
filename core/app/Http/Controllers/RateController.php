<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\RateType;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;


use App\Models\RateChart;
use App\Models\Country;
use App\Models\Company;
use App\Models\ZoneScheme;
use App\Models\ZoneCountry;
use App\Models\Zone;


class RateController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
         $rate_charts = RateChart::where('active', true)->get();
         return view('admin.rates.index', compact('rate_charts'));
    }


    public function getRates()
    {
        $countries = Country::all();
        $rate_charts = RateChart::where('active', true)->get();
                

        return view('admin.rates.get-rates', compact('countries', 'rate_charts'));
    }


    /**
     * Get Rates
     */
    public function searchRates(Request $request)
    {
        $request->validate([
            'country'       => ['required', 'integer', 'exists:App\Models\Country,id'],
            'rate_chart'    => ['nullable', 'integer', 'exists:App\Models\RateChart,id'],
            'weight'  => ['required', 'numeric', 'min:0.01', 'max:999.99'],
            'type'    => ['required', new Enum(RateType::class)],
        ]);
        
        $rate_chart = RateChart::find($request->input('rate_chart'));
        $country = Country::with(['zone' => function($query)use($rate_chart){
                                    $query->where('zone_country.zone_scheme_id', $rate_chart->zone_scheme_id)
                                            ->with(['rates' => function($query){
                                                $query->where('weight', '<=', 500);
                                            }]);
                                }])->find($request->input('country'));

        
                
        return view('admin.rates.show');
    }


    /**
     * Get Zone Schemes
     * */
    public function getZoneSchemes()
    {
        $zone_schemes = ZoneScheme::query()
                                    ->withCount(['zones'])
                                    ->paginate(25);
        return view('admin.rates.zone-schemes', compact('zone_schemes'));
    }

    public function showZoneScheme($id)
    {
        $zone_scheme = ZoneScheme::with(['zones', 'zones.countries'])->findOrFail($id);

        return view('admin.rates.zone-scheme', compact('zone_scheme'));
    }

    public function editZone(Zone $zone)
    {
        $zone->load(['countries']);
        $available_countries = Country::whereNotIn('id', $zone->countries->pluck('id'))->get();
        return view('admin.rates.edit-zone', compact(['zone', 'available_countries']));
    }

    public function updateZone(Request $request, Zone $zone)
    {
        dd($request->all());
    }

    public function addZone(Request $request, ZoneScheme $zone_scheme)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:190']
        ]);
        $zone_scheme->load('zones');
        if($zone_scheme->zones->where('name', $request->input('name'))->count())
        {
            return redirect()->back()->with('error', 'Zone already exists!');
        }

        $zone_scheme->zones()->save(new Zone(['name' => $request->input('name')]));

        return redirect()->route('admin.rates.show-zone-scheme', $zone_scheme->id)
                        ->with('success', 'Zone Added!');
    }


    public function storeZone(Request $request, Zone $zone)
    {
        $request->validate([
            'name'      => ['required', 'string', 'min:1', 'max:190'],
            'countries' => ['required', 'array', 'min:1', 'max:300', 'exists:App\Models\Country,id'],
        ]);
        $zone->load(['scheme', 'countries']);
        $zone->name = $request->input('name');
        $zone->save();
        $requested_countries = collect($request->input('countries', []))->diff($zone->countries->pluck('id'));

        $zone_country_list = [];
        foreach($requested_countries as $item)
        {
            $zone_country_list[] = 
            [
                'zone_scheme_id'    => $zone->scheme->id,
                'zone_id'           => $zone->id,
                'country_id'        => $item,
                'updated_at'        => now(),
                'created_at'        => now(),
            ];
        }
        ZoneCountry::insert($zone_country_list);
    }

    /**
     * Show Countries list
     */
    public function getCountries()
    {
        $countries = Country::paginate(50);
        return view('admin.rates.countries', compact('countries'));   
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies      = Company::all();
        $zone_schemes  = ZoneScheme::all();
          return view('admin.rates.create', compact('companies', 'zone_schemes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:190'],
            'company'   => ['required', 'integer', 'exists:App\Models\Company,id'],
            'zone_scheme' => ['required', 'integer', 'exists:App\Models\ZoneScheme,id'],
            'fuel_charge' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
        
        $rate_chart = new RateChart;
        $rate_chart->name = $request->input('name');
        $rate_chart->company_id = $request->input('company');
        $rate_chart->zone_scheme_id = $request->input('zone_scheme');
        $rate_chart->fuel_charge = (int) $request->input('fuel_charge');
        $rate_chart->active = false;
        $rate_chart->save();

        return redirect()->route('admin.rates.add-rates', $rate_chart->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chart = RateChart::query()
                ->with('zone_scheme')
                ->with('zone_scheme.zone')
                ->with('zone_scheme.zone.rates')
                ->with('zone_scheme.country')
                ->with('zones')
                ->findOrFail($id);

        return view('admin.rates.show');
    }

    public function addRates(RateChart $rate_chart)
    {
        $rate_chart->load('zone_scheme.zones');

        return view('admin.rates.add-rates', compact('rate_chart'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
