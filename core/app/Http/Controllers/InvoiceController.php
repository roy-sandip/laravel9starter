<?php

namespace App\Http\Controllers;


use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Validation\Rule;


use App\Models\Agent;
use App\Models\Billing;
use App\Models\InvoiceTransaction as Transaction;
use App\Models\Shipment;
class InvoiceController extends Controller
{


    protected function attributes()
    {
        return [
            'txn.*.desc' => 'Transaction Description',
            'txn.*.amount' => 'Transaction Amount',
            'txn.*.date' => 'Transaction Date',
            'txn.*.type' => 'Transaction Type',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function agents()
    {
        $agents = Agent::with('lastInvoice')->paginate(25);
        return view('admin.invoices.agents', compact('agents'));
    }


    public function index($id)
    {
        $agent = Agent::findOrFail($id);
        $invoices = $agent->invoices()->latest('id')->paginate(25);

        return view('admin.invoices.index', compact(['agent', 'invoices'])); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $request->validate([
            'start' => ['date_format:d/m/Y', 'required', 'before_or_equal:'.now()->format('d/m/Y')],
            'end'   => ['date_format:d/m/Y', 'required', 'after_or_equal:start'],
        ]);
        $start  = $request->query('start');
        $end    = $request->query('end');

        $from = Carbon::createFromFormat("d/m/Y", $start)->startOfDay();
        $to = Carbon::createFromFormat("d/m/Y", $end)->endOfDay();
        
        
        $agent = Agent::with('lastInvoice')->findOrFail($id);
        $billings = $agent->billings()
                        ->whereNull('invoice_id')
                        ->oldest('bill')
                        ->whereHas('shipment', function(Builder $query)use($from, $to){
                            $query->whereBetween('booking_date', [$from, $to]);
                        })
                        ->with(['shipment', 'shipment.receiver','shipment.service'])->get();
                        
        if(!$billings->count())
        {
            return redirect()->route('admin.invoices.index', $agent->id)
                            ->with('error', 'No billable shipments found for the timespan: '.$start.'-'.$end);
        }

        return view('admin.invoices.create', compact('billings', 'agent', 'start', 'end'));

    }


    public function generate(Request $request, $id)
    {
        $request->validate([
            'start' => ['date_format:d/m/Y', 'required', 'before_or_equal:'.now()->format('d/m/Y')],
            'end'   => ['date_format:d/m/Y', 'required', 'after_or_equal:start'],
            'txn'   => ['array'],
            'txn.*.type' => ['string', 'required', Rule::in(['debit', 'credit'])],
            'txn.*.amount' => ['numeric', 'required', 'min:10', 'max:9999999'],
            'txn.*.date'    => ['string', 'required', 'max:190'],
            'txn.*.desc'    => ['string', 'nullable', 'max:190'],

        ], [], $this->attributes());

        
        $start  = $request->input('start');
        $end    = $request->input('end');

        $from = Carbon::createFromFormat("d/m/Y", $start)->startOfDay();
        $to = Carbon::createFromFormat("d/m/Y", $end)->endOfDay();

        $agent = Agent::with('lastInvoice')->findOrFail($id);
        $billings = $agent->billings()
                        ->whereNull('invoice_id')
                        ->oldest('bill')
                        ->whereHas('shipment', function(Builder $query)use($from, $to){
                            $query->whereBetween('booking_date', [$from, $to]);
                        })
                        //->with(['shipment', 'shipment.receiver','shipment.service'])
                        ->get();
        //check if any billable item found
        if(!$billings->count())
        {
            return redirect()->route('admin.invoices.index', $agent->id)
                            ->with('error', 'No billable shipments found!');
        }
        //check if any unbilled left
        if(!isset($billings->first()->bill))
        {
            return redirect()->route('admin.invoices.create', ['id' => $agent->id, 'start' => $start, 'end' => $end])->with('error', 'Bill First')->withInput();
        }


        //calculate transactions
        $total_credit = 0;
        $total_debit = 0;
        $transactions = [];
        foreach ($request->input('txn', []) as $item)
        {
            $txn = [
                'amount'        => $item['amount'],
                'datetime'      => $item['date'],
                'comment'       => $item['desc'],
                'created_at'    => now(),
            ];



            if($item['type'] == 'debit')
            {   
                $txn['type'] = Transaction::DEBIT;
                $total_debit += $item['amount'];
            }else{
                $total_credit += $item['amount'];
                $txn['type'] = Transaction::CREDIT;
            }

            $transactions[] = $txn;
        }
        
        $total_bill = $billings->sum('bill') + $total_credit + $agent->lastInvoice->balance;
        $total_paid = $billings->sum('paid') + $total_debit;

        $invoice = \DB::transaction(function()use($agent,$from,$to,$total_bill,$total_paid,$transactions, $billings){
            //create invoice
            $invoice = new Invoice;
            $invoice->agent_id = $agent->id;
            $invoice->from = $from;
            $invoice->to = $to;
            $invoice->parent_invoice = $agent->lastInvoice->id;
            $invoice->total_bill = $total_bill;
            $invoice->total_paid = $total_paid;
            $invoice->unlock()
                    ->calculateInvoice()
                    ->save();
            
            //create transactions
            $invoice->transactions()->createMany($transactions);
            //update billings
            Billing::whereIn('id', $billings->pluck('id'))->update(['invoice_id' => $invoice->id]);

            //set last invoice locked
            if($agent->lastInvoice->id)
            {
                $agent->lastInvoice->lock()->save();
            }

            return $invoice;
        });
        

       return redirect()->route('admin.invoices.show', $invoice->id);

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
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with(['transactions', 'billings', 'billings.shipment', 'agent', 'lastInvoice'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $invoice = Invoice::query()
                        ->with([
                            'billings',
                            'billings.shipment', 
                            'billings.shipment.receiver',
                            'billings.shipment.service', 
                            'agent',
                            'transactions',
                            'lastInvoice'
                        ])
                        ->findOrFail($id);
                        

        return view('admin.invoices.edit', compact('invoice'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
         $request->validate([
            'txn'   => ['array'],
            'txn.*.type' => ['string', 'required', Rule::in(['debit', 'credit'])],
            'txn.*.amount' => ['numeric', 'required', 'min:10', 'max:9999999'],
            'txn.*.datetime'    => ['string', 'required', 'max:190'],
            'txn.*.comment'    => ['string', 'nullable', 'max:190'],

        ], [], $this->attributes());

         //save new transations
         $transactions = [];
        foreach ($request->input('txn', []) as $item)
        {
            $transactions[] = [
                'amount'        => $item['amount'],
                'datetime'      => $item['datetime'],
                'comment'       => $item['comment'],
                'created_at'    => now(),
                'type'          => ($item['type'] == 'debit') ? Transaction::DEBIT : Transaction::CREDIT,
            ];
        }

         $invoice->transactions()->createMany($transactions);
         $invoice->load(['transactions']);
         $invoice->reCalculate()->save();
       
         return redirect()->route('admin.invoices.show', $invoice->id)->with('success', 'Bill updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }


    public function updateTransaction(Request $request, $id)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'type' => ['string', 'required', Rule::in(['debit', 'credit'])],
            'datetime'    => ['string', 'required', 'max:190'],
            'comment'    => ['string', 'nullable', 'max:190'],
        ]);

        $transaction = Transaction::findOrFail($id);
        $invoice = $transaction->invoice;
        if($invoice->locked() || !isset($invoice->id))
        {
            return response()->json([
                    'message' => 'Transaction locked. You cannot update it.',
                ], 403);
        }


        $transaction->amount = $request->input('amount');
        $transaction->type($request->input('type'));
        $transaction->datetime = $request->input('datetime');
        $transaction->comment = $request->input('comment');
        if($transaction->isDirty(['amount', 'type']))
        {
            $originalAmount = $transaction->getOriginal('amount');
            
            if($transaction->isDirty('type'))
            {
                //credit to debit (bill to paid)
                if($transaction->isDebit())
                {
                    //remove from invoice credit/bill
                    $invoice->total_bill = $invoice->total_bill - $originalAmount;
                    //add with invoice debit/paid
                    $invoice->total_paid = $invoice->total_paid + $transaction->amount;
                }else{
                    //remove from invoice debit/paid
                    $invoice->total_paid = $invoice->total_paid - $originalAmount;

                    //add with invoice credit/bill
                    $invoice->total_bill = $invoice->total_bill + $transaction->amount;
                }

            }

            if($transaction->isClean('type'))
            {
                if($transaction->isDebit())
                {
                    $invoice->total_paid = $invoice->total_paid + ($transaction->amount - $originalAmount);
                }else{
                    $invoice->total_bill = $invoice->total_bill + ($transaction->amount - $originalAmount);
                }
            }

            $invoice->calculateInvoice()->save();
        }

        $transaction->save();

        return response()->json([
                                'message'   => 'Transaction updated.',
                                'balance'   => $invoice->balance,
                                'transaction' => $transaction
                            ], 200);

    }

    public function deleteTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        $invoice = $transaction->invoice;

        if($invoice->locked() || !isset($invoice->id))
        {
            return response()->json([
                'message' => 'Transaction locked. Deletation not possible.',
            ], 403);
        }

       $invoice->deleteTransaction($transaction)->save();

       return response()->json([
                                'message'   => 'Transaction removed.',
                                'balance'   => $invoice->balance
                            ], 200);
    }



}
