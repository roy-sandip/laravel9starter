<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Agent;
use App\Models\Service;
use App\Models\Address;
use App\Models\Billing;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    protected $rules = [
            'agent_id' => ['required', 'integer', 'exists:App\Models\Agent,id'],
            'service_id' => ['required', 'integer', 'exists:App\Models\Service,id'],
            'weight'    => ['required', 'numeric', 'max:9999'],
            'shipper' => ['array'],
            'shipper.name' => ['required', 'string', 'min:4', 'max:190'],
            'shipper.street' => ['required', 'string', 'min:4', 'max:191'],
            'shipper.city' => ['nullable', 'string', 'min:3', 'max:190'],
            'shipper.zip' => ['nullable', 'string', 'min:4', 'max:190'],
            'shipper.country' => ['required', 'string', 'min:4', 'max:190'],
            'shipper.phone' => ['nullable', 'string', 'min:4', 'max:190'],

            'receiver.name' => ['required', 'string', 'min:4', 'max:190'],
            'receiver.street' => ['required', 'string', 'min:2', 'max:191'],
            'receiver.city' => ['required', 'string', 'min:2', 'max:190'],
            'receiver.zip' => ['required', 'string', 'min:2', 'max:190'],
            'receiver.country' => ['required', 'string', 'min:2', 'max:190'],
            'receiver.phone' => ['nullable', 'string', 'min:2', 'max:190'],

            'description' => ['required', 'string', 'min:2', 'max:99999'],
            'operator' => ['nullable', 'string', 'max:190'],
            'date' => ['required', "date_format:F d Y"],

            'billing' => ['array'],
            'billing.bill' => ['nullable', 'numeric', 'max:999999'],
            'billing.paid' => ['nullable', 'numeric', 'max:999999', 'lte:billing.bill'],
            'billing.comment' => ['nullable', 'string', 'max:190'],

        ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $request->validate([
            'start' => ['nullable', 'date_format:m/d/Y', 'required_with:end', 'after:01/01/2022'],
            'end'   => ['nullable', 'date_format:m/d/Y', 'lte:start'],
        ]);      

        $query = Shipment::query()
                            ->with('agent', 'shipper', 'receiver', 'service')
                            ->latest('awb');
        if(auth()->user()->can('mainbranch'))                            
        {
            $request->whenFilled('agent', function($input)use($query){
                if($input == 'all')
                {
                    return;
                }
                $input = (int) $input;
                $query->where('agent_id', $input);
            });

        }

        $request->whenFilled('service', function($input)use($query){
            if($input == 'all')
            {
                return;
            }
            $input = (int) $input;
            $query->where('service_id', $input);
        });

        $request->whenFilled('start', function($input)use($query){
            $start = new Carbon($input);
            $query->where('booking_date', '>=' ,$start);
        });
        $request->whenFilled('end', function($input)use($query){
            $end = new Carbon($input);
            $query->where('booking_date', '<=' ,$end);
        });
        $shipments = $query->paginate(50)->withQueryString();
        $agents = auth()->user()->can('mainbranch') ? Agent::all() : [];
        $services = Service::all();


        return view('admin.shipments.index', compact(['shipments', 'agents', 'services']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shipment = new Shipment;
        $services = Service::all();
        $user = auth()->user();
        $agents = $user->can('mainbranch') ? Agent::all() : [];
        $shipment = $request->filled('clone') 
                    ?( Shipment::with(['shipper', 'receiver'])->find($request->query('clone')) ?? new Shipment )
                    : new Shipment;
        return view('admin.shipments.create', compact(['shipment', 'agents', 'services']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validateRequest($request);
      
      $user = auth()->user();
       //start storing records
      $shipment =  \DB::transaction(function()use($request, $user){
            //create addresses
            $shipper = new Address();
            $shipper->name = $request->input('shipper.name');
            $shipper->attn = $request->input('shipper.attn', $shipper->name);
            $shipper->street = $request->input('shipper.street');
            $shipper->city = $request->input('shipper.city', 'SYLHET');
            $shipper->zip = $request->input('shipper.zip', '3100');
            $shipper->country = $request->input('shipper.country', 'BANGLADESH');
            $shipper->phone = $request->input('shipper.phone', 'N/G');
            $shipper->type = Address::ADDRESS_SHIPPER;
            $shipper->save();
            
            $receiver = new Address();
            $receiver->name = $request->input('receiver.name');
            $receiver->attn = $request->input('receiver.attn', $receiver->name);
            $receiver->street = $request->input('receiver.street');
            $receiver->city = $request->input('receiver.city');
            $receiver->zip = $request->input('receiver.zip');
            $receiver->country = $request->input('receiver.country');
            $receiver->phone = $request->input('receiver.phone', 'N/G');
            $receiver->type = Address::ADDRESS_RECEIVER;
            $receiver->save();

            //create shipment
            $shipment = new Shipment;
            $shipment->shipper_id = $shipper->id;
            $shipment->receiver_id = $receiver->id;
            $shipment->agent_id = $user->can('mainbranch') 
                                    ? $request->input('agent_id', $user->agent_id) 
                                    : $user->agent_id;

            $shipment->service_id = $request->input('service_id');
            $shipment->shipper_reference = $request->input('shipper_reference');
            $shipment->addUpdate('Package booked', 'Sylhet-BD', now());
            $shipment->weight = $request->input('weight');
            $shipment->description = $request->input('description', 'N/G');
            $shipment->booking_date = Carbon::createFromFormat('F d Y', $request->input('booking_date', date('F d Y')));
            $shipment->operator = $request->input('operator', $user->userid);
            $shipment->user_id = $user->id;
            
            $shipment->save();
            $shipment->setAwB()->save();
            //create billing
            $bill = $request->input('billing.bill');
            $paid = $request->input('billing.paid');
            $billing = new Billing;
            $billing->bill  = $bill;
            $billing->paid  = $paid;
            $billing->due   = ($bill > 0) ? ($bill - $paid) : null;
            $billing->comment = $request->input('billing.comment');
            $shipment->billing()->save($billing);
            
            
            return $shipment;
        });
        

       return redirect()->route('admin.shipments.show', $shipment->id)
                        ->with('success', 'Booking Successful!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['agent', 'service', 'shipper', 'receiver', 'billing']);
        $companies = Company::all();
                            
        return view('admin.shipments.show', compact('shipment', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipment $shipment)
    {
        $services = Service::all();
        $user = auth()->user();
        $billing = $shipment->billing;
        $agents = $user->can('mainbranch') ? Agent::all() : [];
        return view('admin.shipments.edit', compact('shipment', 'agents', 'services', 'billing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipment $shipment)
    {
         $this->validateRequest($request);
         $shipment->load(['shipper', 'receiver']);
         $user = auth()->user();
       \DB::transaction(function()use($request, $shipment, $user){
            //create addresses
            $shipper = $shipment->shipper;
            $shipper->name = $request->input('shipper.name');
            $shipper->attn = $request->input('shipper.attn', $shipper->name);
            $shipper->street = $request->input('shipper.street');
            $shipper->city = $request->input('shipper.city', 'SYLHET');
            $shipper->zip = $request->input('shipper.zip', '3100');
            $shipper->country = $request->input('shipper.country', 'BANGLADESH');
            $shipper->phone = $request->input('shipper.phone', 'N/G');
            $shipper->save();
            
            $receiver = $shipment->receiver;
            $receiver->name = $request->input('receiver.name');
            $receiver->attn = $request->input('receiver.attn', $receiver->name);
            $receiver->street = $request->input('receiver.street');
            $receiver->city = $request->input('receiver.city');
            $receiver->zip = $request->input('receiver.zip');
            $receiver->country = $request->input('receiver.country');
            $receiver->phone = $request->input('receiver.phone', 'N/G');
            $receiver->save();

            //create shipment
            $shipment->shipper_id = $shipper->id;
            $shipment->receiver_id = $receiver->id;
            $shipment->agent_id = $user->can('mainbranch') 
                                    ? $request->input('agent_id', $user->agent_id) 
                                    : $user->agent_id;

            $shipment->service_id = $request->input('service_id');
            $shipment->shipper_reference = $request->input('shipper_reference');
            $shipment->weight = $request->input('weight');
            $shipment->description = $request->input('description', 'N/G');
            $shipment->booking_date = Carbon::createFromFormat('F d Y', $request->input('booking_date', date('F d Y')));
            $shipment->operator = $request->input('operator', $user->userid);
            $shipment->save();
            //create billing
            $bill = $request->input('billing.bill');
            $paid = $request->input('billing.paid');
            $billing = $shipment->billing;
            $billing->bill  = $bill;
            $billing->paid  = $paid;
            $billing->due   = ($bill > 0) ? ($bill - $paid) : null;
            $billing->comment = $request->input('billing.comment');
            $billing->save();
            return $shipment;
        });

       return redirect()->route('admin.shipments.show', $shipment->id)->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        //
    }

    public function rules()
    {
        return $this->rules;
    }
    public function validateRequest(Request $request)
    {
        return $request->validate($this->rules(), [], $this->attributes());
    }

    protected function attributes()
    {
        return [
            'shipper.name'      => 'Sender Name',
            'shipper.street'    => 'Sender Street Address',
            'shipper.city'      => 'Sender City',
            'shipper.zip'       => 'Sender Postcode',
            'shipper.country'   => 'Sender Country',
            'shipper.phone'     => 'Sender Phone',

            'receiver.name'      => 'Receiver Name',
            'receiver.street'    => 'Receiver Street Address',
            'receiver.city'      => 'Receiver City',
            'receiver.zip'       => 'Receiver Postcode',
            'receiver.country'   => 'Receiver Country',
            'receiver.phone'     => 'Receiver Phone',

            'billing.bill'      => 'Bill Amount',
            'billing.paid'      => 'Paid Amount',
            'billing.comment'      => 'Billing Comment',
        ];
    }
}
