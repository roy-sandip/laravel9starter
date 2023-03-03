<?php

namespace App\Http\Controllers;

use App\Models\DHLBill;
use App\Models\DHLTransaction;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaPivot;
use Carbon\Carbon;

class DHLBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = DHLBill::paginate(25);
        return view('admin.dhl.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dhl.create');
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
            'bill_no'   => ['required', 'string', 'max:190', 'unique:App\Models\DHLBill'],
            'bill_date' => ['required', "date_format:d F Y"],
            'total_bill' => ['required', 'numeric', 'max:9999999999'],
            'comment' => ['nullable', 'string', 'max:190'],
            'attachment' => ['array', 'nullable', 'max:5'],
            'attachment.*' => ['required', 'mimetypes:image/*,application/pdf', 'max:10000']
        ]);
        
        $files = $request->file('attachment');

        $bill = new DHLBill;
        $bill->bill_no = $request->input('bill_no');
        $bill->bill_date = Carbon::createFromFormat('d F Y', $request->input('bill_date'));
        $bill->total_bill = $request->input('total_bill');
        $bill->comment = $request->input('comment');
        $bill->save();

        $media_pivot = [];
        if($request->has('attachment'))
        {
            $dir = 'bill_'.$bill->bill_no;
            foreach($files as $file)
            {
                $media = new Media;
                $media->disk('dhl')->upload($file, $dir)->save();
                $media_pivot[] = [
                    'model'     => get_class($bill),
                    'model_id'  => $bill->id,
                    'media_id'  => $media->id,
                ];
            }

            MediaPivot::insert($media_pivot);
        }
        return redirect()->route('admin.dhl.show', $bill->id)->with('success', 'Bill Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DHLBill  $dHLBill
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = DHLBill::with(['attachments', 'transactions', 'transactions.attachment'])->findOrFail($id);
        
        return view('admin.dhl.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DHLBill  $dHLBill
     * @return \Illuminate\Http\Response
     */
    public function edit(DHLBill $dHLBill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DHLBill  $dHLBill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DHLBill $dHLBill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DHLBill  $dHLBill
     * @return \Illuminate\Http\Response
     */
    public function destroy(DHLBill $dHLBill)
    {
        //
    }


    public function storeTransaction(Request $request, $id)
    {
        $request->validate([
            'datetime' => ['required', "date_format:d F Y"],
            'amount' => ['required', 'numeric', 'max:9999999999'],
            'comment' => ['nullable', 'string', 'max:190'],
            'attachment' => ['nullable', 'mimetypes:image/*,application/pdf', 'max:10000']
        ]);

        $bill = DHLBill::findOrFail($id);

        $transaction = new DHLTransaction;
        $transaction->dhlbill_id = $bill->id;
        $transaction->amount = $request->input('amount');
        $transaction->datetime = Carbon::createFromFormat('d F Y', $request->input('datetime'));
        $transaction->comment = $request->input('comment');
        $transaction->save();

        $file = $request->file('attachment');
        $media_pivot = [];
        if($request->has('attachment'))
        {
            $dir = 'bill_'.$bill->bill_no;
           
                $media = new Media;
                $media->disk('dhl')->upload($file, $dir)->save();
                $media_pivot[] = [
                    'model'     => get_class($transaction),
                    'model_id'  => $transaction->id,
                    'media_id'  => $media->id,
                ];
            

            MediaPivot::insert($media_pivot);
        }
        
        $bill->calculateBalance()->save();


        return redirect()->route('admin.dhl.show', $bill->id)->with('success', 'Transaction Added!');
    }




}
