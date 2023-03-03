@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Shipment # {{$shipment->awb}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-message/>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Service</th>
                                    @can('mainbranch')
                                    <th>Agent</th>
                                    @endcan
                                    <th>Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$shipment->shipper_reference}}</td>
                                    <td>{{$shipment->service->name}}</td>
                                    @can('mainbranch')
                                    <td>{{$shipment->agent->name}}</td>
                                    @endcan
                                    <td>{{$shipment->weight}} KG</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="card card-info border border-info" style="height:100%">
                                <div class="card-header p-1">
                                    <h5 class="card-title">Shipper</h5>
                                </div>
                                <div class="card-body"  style="padding-bottom: 0;">
                                    <b>{{$shipment->shipper->name}}</b><br>
                                    
                                        {!!$shipment->shipper->getAddress()!!} <br>
                                        <i class="fas fa-phone-alt"></i> {{$shipment->shipper->phone}}
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info border border-info" style="height: 100%;">
                                <div class="card-header p-1">
                                    <h5 class="card-title">Receiver</h5>
                                </div>
                                <div class="card-body" style="padding-bottom: 0;">
                                    <b>{{$shipment->receiver->name}}</b><br>
                                        {!!$shipment->receiver->getAddress()!!} <br>
                                        <i class="fas fa-phone-alt"></i> {{$shipment->receiver->phone}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>    
                    <div class="row">
                        <div class="col-md-6">
                            <b>Description: </b>
                            {{$shipment->description}}
                        </div>
                        
                        <div class="col-md-6">
                            <b>Billing Info</b>
                            <table class="table table-stripped table-sm">
                                <tbody>
                                    @if(!$shipment->agent->is_MainBranch())
                                    <tr>
                                        <td>Invoice ID</td> <td>{{$shipment->billing->invoice_id}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Total Bill</td> <td>{{$shipment->billing->getBill()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Paid Amount</td> <td>{{$shipment->billing->getPaid()}}</td>
                                    </tr>
                                    <tr>
                                        <td>Due Amount</td> <td>{{$shipment->billing->getDue()}}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <b>Operator: </b> {{$shipment->operator}}
                        </div>
                        <div class="col">
                            <b>Date: </b> {{$shipment->booking_date->format('F d, Y')}}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{route('admin.pdf.shipments.booking-receipt', $shipment->id)}}" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Receipt</a>
                            <a href="{{route('admin.pdf.shipments.transport-copy', $shipment->id)}}" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Transport Copy</a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-updates">Updates</button>
                            
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{route('admin.shipments.edit', $shipment->id)}}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{route('admin.shipments.create')}}?clone={{$shipment->id}}" class="btn btn-warning btn-sm">Clone</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>








<div class="modal fade" id="modal-updates" style="display: none;" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Updates for shipment # {{$shipment->awb}}</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 <span aria-hidden="true">×</span>
</button>
</div>
<div class="modal-body table-responsive">
    <form action="{{route('admin.shipments.tracking-reference.update', $shipment->id)}}" method="POST" id="referenceForm">
        <div class="row">
            <div class="col-4 offset-1">
                <div class="form-group">
                    <select name="company" id="" class="col-4 form-control form-control-sm select2 @error('company') is-invalid @enderror" data-placeholder="Select Company" style="width: 100%;" required>
                        <option disabled selected></option>
                        @foreach($companies as $item)
                        <option value="{{$item->key}}" @if($shipment->connected_company_key == $item->key) selected @endif >{{$item->name}}</option>
                        @endforeach
                    </select>
                
                    
                
                </div>
            </div>
            <div class="col-4">
                <input type="text" name="reference" class="form-control form-control-sm" value="{{$shipment->connected_reference_no}}" required>
            </div>
            <div class="col-2">
                    <button class="btn btn-primary btn-sm" type="submit">Add</button>
                    @isset($shipment->connected_company_key)
                    <button class="btn btn-danger btn-sm" id="removeReference" type="button">Remove</button>
                    @endisset
            </div>
        </div>
        
    </form>
    <hr>
    <table class="table table-sm table-hover" id="updates">
        <thead>
            <tr>
                <td class="d-none">key</td>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->updates as $item)
            <tr>
                <td class="d-none">{{$loop->index}}</td>
                <td>{{$item->date}}</td>
                <td>{{$item->time}}</td>
                <td>{{$item->activity}}</td>
                <td>{{$item->location}}</td>
            </tr>
            @endforeach
        </tbody>
            <input type="hidden" class="tabledit-input" name="_method" value="put">
    </table>
    <div class="row">
       <form id="createNewUpdate" action="{{route('admin.shipments.tracking-updates.create', $shipment->id)}}" method="POST" class="col">
        @csrf
        <table class="table table-sm" width="100%">
            <tr>
                <td><input name="activity" type="text" class="form-control" placeholder="Activity"></td>
                
                <td>
                    <div class="input-group date" id="datetimepicker1">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                         <input type="text" name="date" class="form-control" value="{{date('m/d/Y')}}" />
                    </div>
                </td>
                <td rowspan="2" style="vertical-align: middle;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-cloud"></i> Save</button>
                </td>
            </tr>
            <tr>
                <td><input name="location" type="text" list="locationList" class="form-control" placeholder="Location"></td>
                <td> 
                    <div class="input-group clockpicker">
                        <div class="input-group-prepend" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                        <input type="text" name="time" class="form-control" value="{{date('h:iA')}}" placeholder="Time">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </td>
                
            </tr>
        </table>
          
          <datalist id="locationList">
              <option>Sylhet-BD</option>
              <option>Dhaka-BD</option>
          </datalist>

         
        </form>
    </div>
</div>
<div class="modal-footer justify-content-between">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

</div>
</div>

</div>

</div>

@stop


@section('js')
<script>
$('.select2').select2();
function editableTable()
{
        $('#updates').Tabledit({
        url: '{{route("admin.shipments.tracking-updates.update", $shipment->id)}}',
        dataType: 'json',
        columns: {
            identifier: [0, 'key'],
            editable: [[1, 'date'], [2, 'time'], [3, 'activity'], [4, 'location']]
        },
        restoreButton: false,
        onSuccess: function(data, textStatus, jqXHR){
            toastr.success('Status Updated!');
        },
        onFail: function(jqXHR, textStatus, errorThrown){
            toastr.error(jqXHR.responseJSON.message);
        },


        buttons: {
            edit: {
                class: 'btn btn-sm btn-default',
                html: '<span class="fas fa-edit"></span>',
                action: 'edit'
            },
            delete: {
                class: 'btn btn-sm btn-default',
                html: '<span class="fas fa-trash-alt"></span>',
                action: 'delete'
            },
            save: {
                class: 'btn btn-sm btn-success',
                html: 'Save'
            },
            restore: {
                class: 'btn btn-sm btn-warning',
                html: 'Restore',
                action: 'restore'
            },
            confirm: {
                class: 'btn btn-sm btn-danger',
                html: 'Confirm'
            }
        }
    });
}
editableTable();




    $('#datetimepicker1').datepicker({
      autoclose: true,      
      singleDatePicker: true,
      todayBtn: 'linked'
    })
    $('.clockpicker').clockpicker({
        placement: 'top',
        align: 'left',
        autoclose: true,
        twelvehour: true,
        default: 'now'
    });

$("#createNewUpdate").on('submit', function(event){
    event.preventDefault();
    var submitButton = $(this).find('button');
    var form = this;
    makeAjax(
            form, 
            //before send
            function(){
                startSpinner(submitButton);
            },
            //complete
            function(){
                stopSpinner(submitButton);
            },
            //success
            function(response){
                laravelSuccess(response);
                var row = '';
                var csrf = '{{csrf_token()}}';
                $(response.updates).each(function(index, element){
                    row += '<tr>'
                    row += '<td class="d-none">'+index+'</td>';
                    row += '<td>'+element.date+'</td>';
                    row += '<td>'+element.time+'</td>';
                    row += '<td>'+element.activity+'</td>';
                    row += '<td>'+element.location+'</td>';
                    row += '</tr>';
                });
                $("#updates tbody").html(row);
                editableTable();
                $(form).trigger('reset');
            },
            //error
            function(error){
                laravelError(error);
                stopSpinner(submitButton);
            }
        );
});


$("#referenceForm").on('submit', function(event){
    event.preventDefault();
    makeAjax(
            this,
            //beforesend
            function(){
                startSpinner();
            },
            //complete
            function(){
                stopSpinner();
            },
            //success
            function(response){
                laravelSuccess(response);
            },
            //error
            function(error){
                laravelError(error);
            }
        );
})

//remove reference
$('#removeReference').on('click', function(event){
    var button = this;
    $.ajax({
        url: '{{route("admin.shipments.tracking-reference.remove", $shipment->id)}}',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            startSpinner(button);
        },
        complete: function(){
            stopSpinner(button);
        },
        success: function(response){
            laravelSuccess(response);
        },
        error: function(error){
            laravelError(error);
        }

    });
});
</script>
@endsection