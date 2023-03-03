<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Company;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::paginate(25);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        if(count($companies) == 0)
        {
            return redirect()->route('admin.companies.index')->with('status', 'Sync company first.');
        }
        return view('admin.services.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:128', 'unique:App\Models\Service'],
            'company' => ['required', 'string', 'exists:App\Models\Company,key']
        ]);
        $company = Company::query()->where('key', $data['company'])->first();

        $service = new Service;
        $service->name = $data['name'];
        $service->company_key = $company->key;
        $service->public_name = $request->input('public_name', $data['name']);
        $service->save();
        return redirect()->route('admin.services.show', $service->id)->with('success', 'New Service Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
         return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $companies = Company::all();
        return view('admin.services.edit', compact(['service', 'companies']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:128', 'unique:App\Models\Service,name,'.$service->id],
            'company' => ['required', 'string', 'exists:App\Models\Company,key']
        ]);

        //check if company changed
        if($service->company_key != $data['company'])
        {
            $company = Company::where('key', $data['company'])->first();
            $service->company_key = $company->key;
        }
        $service->name = $data['name'];
        $service->public_name = $request->input('public_name', $data['name']);
        $service->save();
        return redirect()->route('admin.services.show', $service->id)->with('success', 'Service Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $shipmentsCount = $service->shipments()->count();
        if($shipmentsCount > 0)
        {
            return redirect()->back()->with('error', 'This service has '. $shipmentsCount .' shipment. You cannot delete it now.');
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service Deleted!');
    }
}
