<?php
$totalBill = $billings->sum('bill');
$totalPaid = $billings->sum('paid');
$subTotal  = ($totalBill + $agent->lastInvoice->balance) - $totalPaid;
?>

@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">New Invoice</h1>
    <p>
        <table class="table">
            <tr>
                <td>Agent Name</td> <td>{{$agent->name}}</td>
            </tr>
            <tr>
                <td>Agent Company</td> <td>{{$agent->company}}</td>
            </tr>
        </table>
    </p>

@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        
                    </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped table-hover" id="billingTable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>AWB</th>
                                <th>Receiver</th>
                                <th>Destination</th>
                                <th>Service</th>
                                <th>Description</th>
                                <th>Weight(KG)</th>
                                <th>
                                    <table class="table table-sm text-right" style="margin:0">
                                        <tr>
                                            <td style="padding:0;border: none;">Bill</td>
                                            <td style="padding:0;border:none;">Paid</td>
                                            <td style="padding:0;border:none;">Remark</td>
                                        </tr>
                                    </table>
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billings as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->shipment->booking_date->format('d M, Y')}}</td>
                                <td>{{$item->shipment->awb}}</td>
                                <td>{{$item->shipment->receiver->name}}</td>
                                <td>{{$item->shipment->receiver->country}}</td>
                                <td>{{$item->shipment->service->name}}</td>
                                <td style="max-width:200px;">{{$item->shipment->description}}</td>
                                <td>{{$item->shipment->weight}}</td>
                                <td>
                                    <input type="hidden" value="{{$item->getBill()}}">
                                    <form action="{{route('admin.billings.update', $item->id)}}" method="post" class="form-inline billForm">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="agent_id" value="{{$agent->id}}">
                                        <table class="table table-sm" style="padding: 0;margin: 0;">
                                            <tr>
                                                <td style="border: none;padding: 0 2px;">
                                                    <div class="form-group is-loading">
                                                    <input name="bill" type="number" min="0" data-id="{{$item->id}}" data-type="bill" data-amount="{{$item->getBill()}}" class="form-control text-right numeric" value="{{$item->getBill()}}" onchange="updateBill(this)" style="width: 100px;" placeholder="Bill" >
                                                      <div class="spinner-border spinner-border-sm text-info" style="display:none;"></div>
                                                    </div>
                                                </td>
                                                <td style="border: none;padding: 0 2px;">
                                                    <div class="form-group is-loading">
                                                        <input name="paid" type="number" min="0" data-id="{{$item->id}}" data-type="paid" data-amount="{{$item->getPaid()}}" class="form-control text-right numeric" value="{{$item->getPaid()}}" onchange="updateBill(this)" style="width: 100px;" placeholder="Paid">
                                                        <div class="spinner-border spinner-border-sm text-info" style="display:none;"></div>
                                                    </div>
                                                </td>
                                                <td style="border:none;padding: 0;">
                                                    <div class="form-group is-loading">
                                                        <input type="text" class="form-control" name="comment" value="{{$item->comment}}" onchange="updateBill(this)" placeholder="Comment">
                                                        <div class="spinner-border spinner-border-sm text-info" style="display:none;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        
                                        
                                    </form>

                                    
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center table-responsive">
                    <div class="row">
                       <div class="col-12">
                           <table class="table table-sm" style="text-align:right;font-size: 18px;">
                            <thead></thead>
                            <tbody>
                               <tr class="bg-dark">
                                   <td colspan="6">Total</td>
                                   <td colspan="2" id="totalBill">{{$totalBill}}</td>
                                   <td colspan="2" class="totalPaid">{{$totalPaid}}</td>
                               </tr>
                               <tr class="bg-secondary">
                                   <td colspan="6">Paid (-)</td>
                                   <td colspan="2" class="totalPaid">{{$totalPaid}}</td>
                                   <td colspan="2"></td>
                               </tr>
                               @if($agent->lastInvoice->id)
                               <tr>
                                   <td colspan="6">Last invoice ({{$agent->lastInvoice->id}})</td>
                                   <td colspan="2">{{$agent->lastInvoice->balance}}</td>
                                   <td colspan="2"></td>
                               </tr>
                               @endif
                           </tbody>
                           </table>
                       </div>
                    </div>

                       <div class="row">
                           <div class="col-12">
                            <form action="{{route('admin.invoices.generate', $agent->id)}}" method="post" id="invoiceForm" onkeydown="return event.key != 'Enter';" >
                                @csrf
                                <input type="hidden" name="start" value="{{$start}}">
                                <input type="hidden" name="end" value="{{$end}}">
                               <table class="table table-sm" id="transactions">
                                @foreach(old('txn', []) as $key => $item)
                                    <?php
                                    if($item['type'] == 'debit')
                                    {
                                        $subTotal = $subTotal - $item['amount'];
                                    }else{
                                        $subTotal = $subTotal + $item['amount'];
                                    }

                                    ?>
                                <tr>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="$(this).closest('tr').remove()" type="button"><i class="fas fa-times"></i></button>
                                    </td>
                                    <td>
                                        <input name="txn[{{$key}}][desc]" type="text" class="form-control" max="190" value="{{$item['desc']}}" placeholder="Description">
                                    </td>
                                    <td>
                                        <div class="input-group"><div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div><input name="txn[{{$key}}][date]" type="text" value="{{$item['date']}}" class="form-control" max="190" placeholder="Date"></div>
                                    </td>
                                    <td>
                                        <select name="txn[{{$key}}][type]" id=""  onchange="calculateTXN(this)" class="form-control type">
                                            <option value="credit" @if($item['type'] == 'credit') selected @endif >+</option>
                                            <option value="debit"  @if($item['type'] == 'debit') selected @endif >-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group"><div class="input-group-prepend"><div class="input-group-text">TK.</div></div><input name="txn[{{$key}}][amount]" type="number" value="{{$item['amount']}}" data-amount="{{$item['amount']}}" class="form-control text-right numeric amount @error('txn.'.$key.'.amount') is-invalid @enderror" placeholder="Amount" min="0" max="99999999"  onchange="calculateTXN(this)" required=""> </div>
                                    </td>
                                </tr>
                                @endforeach
                               </table>
                               
                           </form>
                           </div>
                       </div>
                       
                       <div class="row">
                           <div class="col-12">
                               <table class="table text-right" style="font-size:1.5em;font-weight:bold;">
                                   <tr class="bg-info">
                                       <td colspan="6">Invoice Total</td>
                                       <td colspan="1" id="subTotal">{{ $subTotal }}</td>
                                   </tr>
                                   
                               </table>
                           </div>
                       </div>

                       <div class="row">
                           <div class="col">
                            <button class="btn btn-outline-primary text-right" style="float: left;" onclick="addRow(rowid)" type="button">Add Transaction</button>
                               <button class="btn btn-success" type="submit" form="invoiceForm" style="float: right;">Submit</button>
                           </div>
                       </div>
                   

                </div>
            </div>
        </div>
    </div>

<style>

</style>


@endsection

@section('js')
<script>
    var totalBill = {{$totalBill}};
    var totalPaid = {{$totalPaid}};
    var subTotal = {{$subTotal}};

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


    function updateBill(input)
    {
        var form = $(input).closest('form')[0];
        var amount = $(input).data('amount');
        var type = $(input).data('type');
        var changed = $(input).val();

        makeAjax(form,
            //beforesend
            function(){
                $(input).attr('disabled', true).removeClass('is-valid').next().show();
            },
            //complete
            function(){
              $(input).attr('disabled', false).next().hide(); 
            },
            //success
            function(response){
                $(input).addClass('is-valid').removeClass('is-invalid');
                if(type == 'bill'){
                    calculateBill(changed, Number(amount));
                }
                if(type == 'paid')
                {
                 calculatePaid(changed, Number(amount));   
                }
                calculateSubtotal();
                $(input).data('amount', changed);
            },
            function(error){
                $(input).val($(input).data('amount'));
                $(input).addClass('is-invalid');
                laravelError(error);
            }

            );
    }

    $(".billForm").on('submit', function(event){
        event.preventDefault();
    })
   

    function calculateBill(newBill, oldBill)
    {
        newBill = Number(newBill);
        oldBill = Number(oldBill);
        totalBill = Number(totalBill);
        var total = (totalBill-oldBill) + newBill;
            subTotal = (subTotal - oldBill) + newBill;
        console.log('Bill: ('+totalBill+'-'+oldBill+')+'+newBill+'='+subTotal);
        totalBill = total;
        $("#totalBill").html(totalBill);
    }

    function calculatePaid(newAmount, oldAmount)
    {
        newAmount = Number(newAmount);
        oldAmount = Number(oldAmount);
        totalPaid = Number(totalPaid);
        var total = (totalPaid - oldAmount) + newAmount;
            subTotal =  (oldAmount - newAmount) + subTotal;
        console.log('Paid: ('+totalPaid+'-'+oldAmount+')+'+newAmount+'='+subTotal);
        totalPaid = total;
        $(".totalPaid").html(totalPaid);
    }

    function calculateSubtotal()
    {
        $("#subTotal").html(subTotal);
    }

    function calculateTXN(input)
    {
        var tag = $(input).prop('tagName');
        if(tag == 'SELECT')
        {
            var type = $(input).val();
            var input = $(input).closest('tr').find('.amount');
            var amount = Number($(input).val());


            if(type == 'credit')
            {
                subTotal  = (amount * 2) + subTotal;
                console.log(type+': ('+ amount + 'x'+2+') + '+subTotal+'= '+subTotal);
            }

            if(type == 'debit')
            {
                subTotal  = subTotal - (amount * 2);
                console.log(type+': '+ subTotal + '-('+amount+'x'+2+') = '+subTotal);
            }

        }

        if(tag == 'INPUT')
        {
            var type = $(input).closest('tr').find('.type').val();
            var oldAmount = Number($(input).data('amount'));
            var newAmount = Number($(input).val());
            $(input).data('amount', newAmount);
            
            if(type == 'credit')
            {
                subTotal  = (subTotal - oldAmount) + newAmount;
                console.log(type+': ('+ subTotal + '-'+oldAmount+') + '+newAmount+'= '+subTotal);
            }

            if(type == 'debit')
            {
                subTotal = subTotal - (newAmount - oldAmount);
                console.log(type+': '+ subTotal + '-('+newAmount+'-'+oldAmount+') = '+subTotal);
            }

        }



        
        
        calculateSubtotal();

    }

    var rowid = {{count(old('txn', []))}};
    function addRow(id)
    {
        var row = '<tr>';
            row += '<td><button class="btn btn-sm btn-outline-danger" onclick="$(this).closest(\'tr\').remove()" type="button"><i class="fas fa-times"></i></button></td>';
            row += '<td><input name="txn['+id+'][desc]" type="text" class="form-control" max="190" placeholder="Description"/></td>';
            row += '<td><div class="input-group"><div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div><input name="txn['+id+'][date]" type="text" class="form-control datepicker" max="190" placeholder="Date"/></div></td>';
            row += '<td><select name="txn['+id+'][type]" onchange="calculateTXN(this)" class="form-control type"><option value="credit">+</option><option value="debit" selected>-</option></select></td>';
            row += '<td><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">TK.</div></div><input name="txn['+id+'][amount]" data-type="debit" data-amount="0" onchange="calculateTXN(this)" type="number" value="" class="form-control text-right numeric amount" placeholder="Amount" min="0" max="99999999" required/> </div></td>';                    
            row += '</tr>';

            $("#transactions").append(row);
            rowid = rowid + 1;
            calendarPicker();
    }



function calendarPicker(){
    $('.datepicker').datepicker({
        format: "dd MM, yyyy",
        endDate: "now",
        maxViewMode: 2,
        todayBtn: "linked",
        autoclose: true
    });
}
    
</script>
@endsection