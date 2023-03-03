
@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Bill # {{$invoice->id}}</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   <div class="row">
                       <div class="col-md-6">
                           <table class="table table-border table-sm">
                               <tr>
                                   <td>Agent</td> <td><b>{{$invoice->agent->name}}</b>, {{$invoice->agent->company}}</td>
                                   
                               </tr>
                               
                           </table>
                       </div>
                       <div class="col-md-6 text-right">
                           <table class="table table-border">
                               <tr>
                                   <td>Period</td><td>{{$invoice->period()}}</td>
                               </tr>
                           </table>
                       </div>
                   </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped table-hover" id="billingTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>AWB</th>
                                <th>Receiver</th>
                                <th>Destination</th>
                                <th>Service</th>
                                <th>Description</th>
                                <th>Weight(KG)</th>
                                <th>Bill</th>
                                <th>Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->billings as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->shipment->booking_date->format('d M, Y')}}</td>
                                <td>{{$item->shipment->awb}}</td>
                                <td>{{$item->shipment->receiver->name}}</td>
                                <td>{{$item->shipment->receiver->country}}</td>
                                <td>{{$item->shipment->service->name}}</td>
                                <td>{{$item->shipment->description}}</td>
                                <td>{{$item->shipment->weight}}</td>
                                <td>{{$item->getBill()}}</td>
                                <td>{{$item->getPaid()}}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-body text-right table-responsive">
                    <div class="row">
                       <div class="col-12">
                           <table class="table table-sm" style="text-align:right;font-size: 18px;">
                            <thead></thead>
                            <tbody>
                               <tr class="bg-dark">
                                    <td></td>
                                   <td colspan="6">Total</td>
                                   <td colspan="2" id="totalBill">{{$invoice->getBill()}}</td>
                                   <td colspan="2" class="totalPaid">{{$invoice->getPaid()}}</td>
                               </tr>
                               <tr class="bg-secondary">
                                    <td></td>
                                   <td colspan="6">Paid (-)</td>
                                   <td colspan="2" class="totalPaid">{{$invoice->getPaid()}}</td>
                                   <td colspan="2"></td>
                               </tr>
                               @if($invoice->lastInvoice->id)
                               <tr>
                                    <td></td>
                                   <td colspan="6"> 
                                    <a href="{{route('admin.invoices.show', $invoice->lastInvoice->id)}}">
                                        Last Bill ({{$invoice->lastInvoice->id}})
                                    </a>   
                                    </td>
                                   <td colspan="2">@if($invoice->lastInvoice->balance)+@endif {{$invoice->lastInvoice->balance}}</td>
                                   <td colspan="2"></td>
                               </tr>
                               @endif
                                

                                @foreach($invoice->transactions as $item)
                                    <tr>
                                        <td colspan="1">{{$item->datetime}}</td>
                                        <td colspan="6">{{$item->comment}}</td>
                                        <td colspan="2">
                                            @if($item->type == 'c') + @else - @endif
                                            {{$item->amount}}
                                        </td>
                                        <td colspan="1"></td>
                                    </tr>
                                @endforeach
                           </tbody>
                           <tfoot style="font-size:1.5em;font-weight:bold;">
                                   <tr class="bg-info">
                                       <td colspan="8">Closing Balance</td>
                                       <td colspan="1" id="subTotal">{{ $invoice->getBalance() }}</td>
                                       <td></td>
                                   </tr>
                           </tfoot>
                           </table>
                       </div>
                    </div>            
                </div>

                <div class="card-footer">
                    <a href="{{route('admin.pdf.billing-invoice', $invoice->id)}}" target="_blank" class="btn btn-default">
                        <i class="fas fa-print"> Print</i>
                    </a>
                    @if($invoice->locked())
                    <a href="#" class="btn btn-default">Unlock</a>
                    @else
                    <a href="{{route('admin.invoices.edit', $invoice->id)}}" class="btn btn-outline-warning"><i class="fas fa-pencil"></i> Edit</a>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-lock"></i> Lock
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>


@endsection

@section('js')
<script>

    var datatable = $('#billingTable').DataTable({
        stateSave: true,
        dom: '<"row" <"col" l> <"col" f> <"col" p>>t',
        columnDefs: [
                { orderable: true, targets: 0, searchable:false },
                { orderable: true, targets: 1, searchable:false },
                { orderable: true, targets: 2 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
                { orderable: false, targets: 5},
                { orderable: false, targets: 6, searchable: false },
                { orderable: false, targets: 7, searchable: false },
                { orderable: true, targets: 8, searchable:false, orderDataType: "dom-text" },
        ],

    });




   





    
</script>
@endsection