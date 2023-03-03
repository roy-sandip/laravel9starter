<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'bill'  => ['required', 'numeric', 'max:9999999', 'min:0'],
            'paid'  => ['nullable', 'numeric', 'max:9999999', 'min:0', 'lte:bill'],
            'comment' => ['nullable', 'string', 'max:190'],
            'agent_id' => ['required', 'integer'],
        ]);

          $billing = Billing::with('invoice')
                            ->whereHas('shipment', function(Builder $query)use($request){
                                $query->where('agent_id', $request->agent_id);
                            })
                            ->findOrFail($id);
        
        if($billing->invoice->locked())
        {
            return response()->json([
                                'message' => 'Already invoiced. Not editable.'
                                ], 403);
        }

        $billing->bill = $request->bill;
        $billing->paid = $request->paid;
        $billing->comment = $request->comment;
        $billing->save();

        return response()->json([
            'message' => 'Bill updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
      
    }



}
