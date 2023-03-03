@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Shipment # {{$shipment->awb}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-message/>
                <form action="{{route('admin.shipments.update', $shipment->id)}}" method="POST" autocomplete="false">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-stripped text-dark">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        @can('mainbranch')
                                        <th>Agent</th>
                                        @endcan
                                        <th>Service</th>
                                        <th>Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="reference" class="form-control form-control-sm @error('reference') is-invalid @enderror" value="{{old('reference', $shipment->shipper_reference)}}">
                                        </td>
                                       
                                        @can('mainbranch')
                                        <td>
                                            <select name="agent_id" id="" class="form-control form-control-sm select2 @error('agent_id') is-invalid @enderror" data-placeholder="Select Agent" required>
                                                <option disabled selected></option>
                                                @foreach($agents as $item)
                                                <option value="{{$item->id}}" @if(old('agent_id', $shipment->agent_id) == $item->id) selected @endif >{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        @endcan
                                         <td>
                                            <select name="service_id" id="" class="form-control form-control-sm select2 @error('service_id') is-invalid @enderror" data-placeholder="Select Service" required>
                                                <option disabled selected></option>
                                                @foreach($services as $item)
                                                <option value="{{$item->id}}" @if(old('service_id', $shipment->service_id) == $item->id) selected @endif >{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" min="0" name="weight" step="any" class="form-control form-control-sm @error('weight') is-invalid @enderror" value="{{old('weight', $shipment->weight)}}" >
                                                <div class="input-group-append">
                                                    <div class="input-group-text">KG</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-info border border-info">
                                <div class="card-header">
                                    <h5 class="card-title">Sender</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input name="shipper[name]" type="text" class="form-control form-control-sm @error('shipper.name') is-invalid @enderror" placeholder="Sender Name" value="{{old('shipper.name', $shipment->shipper->name)}}" required>
                                    </div>
                                    <div class="form-group">
                                      <textarea name="shipper[street]" cols="30" rows="5" class="form-control form-control-sm @error('shipper.street') is-invalid @enderror" placeholder="Street Address">{{old('shipper.street', $shipment->shipper->street)}}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <input name="shipper[city]" type="text" class="form-control form-control-sm @error('shipper.city') is-invalid @enderror" placeholder="City" value="{{old('shipper.city', $shipment->shipper->city)}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="shipper[zip]" type="text" class="form-control form-control-sm @error('shipper.zip') is-invalid @enderror" placeholder="Post Code" value="{{old('shipper.zip', $shipment->shipper->zip)}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="shipper[country]" type="text" class="form-control form-control-sm @error('shipper.country') is-invalid @enderror" placeholder="Country" value="{{old('shipper.country', $shipment->shipper->country)}}">
                                    </div>
                                    <div class="form-group">
                                        <input name="shipper[phone]" type="text" class="form-control form-control-sm @error('shipper.phone') is-invalid @enderror" placeholder="Phone" value="{{old('shipper.phone', $shipment->shipper->phone)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info border border-info">
                                <div class="card-header">
                                    <h5 class="card-title">Receiver</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input name="receiver[name]" type="text" class="form-control form-control-sm @error('receiver.name') is-invalid @enderror" placeholder="Receiver Name" value="{{old('receiver.name', $shipment->receiver->name)}}" required>
                                    </div>
                                    <div class="form-group">
                                      <textarea name="receiver[street]" cols="30" rows="5" class="form-control form-control-sm @error('receiver.street') is-invalid @enderror" placeholder="Street Address">{{old('receiver.street', $shipment->receiver->street)}}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <input name="receiver[city]" type="text" class="form-control form-control-sm @error('receiver.city') is-invalid @enderror" placeholder="City" value="{{old('receiver.city', $shipment->receiver->city)}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="receiver[zip]" type="text" class="form-control form-control-sm @error('receiver.zip') is-invalid @enderror" placeholder="ZIP" value="{{old('receiver.zip', $shipment->receiver->zip)}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="receiver[country]" type="text" class="form-control form-control-sm @error('receiver.country') is-invalid @enderror" placeholder="Country" value="{{old('receiver.country', $shipment->receiver->country)}}">
                                    </div>
                                    <div class="form-group">
                                        <input name="receiver[phone]" type="text" class="form-control form-control-sm @error('receiver.phone') is-invalid @enderror" placeholder="Phone" value="{{old('receiver.phone', $shipment->receiver->phone)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Item Description</label> <span class="text-red">Separate each item with comma (,)</span>
                            <textarea name="description" id="" cols="30" rows="5" class="form-control form-control-sm @error('description') is-invalid @enderror">{{old('description', $shipment->description)}}</textarea>
                        </div>

                        @can('mainbranch')
                        <div class="col-md-4 offset-md-1">
                            <b>Billing Information</b>
                            <hr>
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Total Bill</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" name="billing[bill]" max="99999999" class="form-control form-control-sm @error('billing.bill') is-invalid @enderror totalBill billing" value="{{old('billing.bill', $billing->getBill())}}">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Paid</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" name="billing[paid]" max="99999999" class="form-control form-control-sm @error('billing.paid') is-invalid @enderror totalPaid billing" value="{{old('billing.paid', $billing->getPaid())}}">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div>
                            <!--  <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Discount</label>
                                 <div class="input-group col-sm-10 col-md-8">
                                    <input type="number" max="99999999" class="form-control form-control-sm">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div> -->
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Due</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" max="99999999" class="form-control form-control-sm totalDue" value="{{$billing->getDue()}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                        Taka
                                         </span>
                                    </div> 
                                 </div>
                             </div>

                              <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Comment</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="text"  name="billing[comment]" max="190" class="form-control form-control-sm" value="{{old('billing.comment', $billing->comment)}}">
                                 </div>
                             </div>

                        </div>
                        @endcan
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="" class="col-sm-4">Operator</label>
                                <input type="text" name="operator" class="form-control form-control-sm col-sm-8 @error('operator') is-invalid @enderror" value="{{old('operator', $shipment->operator)}}">
                            </div>
                        </div>

                        <div class="col-md-4 offset-md-1">
                            <div class="form-group row">
                                <label for="" class="col-sm-4">Date</label>
                                <div class="input-group input-group-sm date col-sm-8" id="datetimepicker1" data-target-input="nearest">
                                     <input type="text" name="date" class="form-control form-control-sm datetimepicker-input @error('date') is-invalid @enderror" data-target="#datetimepicker1" value="{{old('date', $shipment->date->format('F d Y'))}}" />
                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <button class="btn btn-default" type="reset">Clear</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#datetimepicker1').datetimepicker({
            format: 'MMMM D YYYY'
        });



        
        $(".billing").on('keyup', function (e){
            var due =  $('.totalBill').val() - $('.totalPaid').val();
            $('.totalDue').val(due);
        });



    });
</script>
@endsection