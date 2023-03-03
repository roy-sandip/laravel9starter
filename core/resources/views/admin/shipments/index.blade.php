@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Shipments Archive</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <form action="#" method="GET" class="row">
                        <div class="form-group col">
                            <label for="">Total: {{$shipments->total()}}</label>
                        </div>
                        <div class="form-group col-sm-4 col-md-3">
                            <div class="input-group">
                                <div class="input-daterange input-group input-group-sm" id="datepicker">
                                    <input type="text" class="form-control-sm form-control" name="start" value="{{request()->query('start')}}" />
                                    <span class="input-group-append">
                                        <span class="input-group-text">To</span>
                                    </span>
                                    <input type="text" class="form-control form-control-sm" name="end" value="{{request()->query('end')}}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-2">
                            <select name="service" id="" class="form-control form-control-sm select2 @error('service') is-invalid @enderror" data-placeholder="Select Service" required>
                                <option selected value="all">All Service</option>
                                @foreach($services as $item)
                                <option value="{{$item->id}}" @if(request()->query('service') == $item->id) selected @endif >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('mainbranch')
                        <div class="form-group col-sm-4 col-md-2">
                          <select name="agent" id="" class="form-control form-control-sm select2 @error('agent') is-invalid @enderror" data-placeholder="Select Agent" required>
                                <option selected value="all">All Agents</option>
                                @foreach($agents as $item)
                                <option value="{{$item->id}}" @if(request()->query('agent') == $item->id) selected @endif >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div> 

                       
                        @endcan

                        <div class="form-group col">
                            <button class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>
                </div>


                <div class="card-body table-responsive">
                   <x-message/>
                    <table class="table table-stripped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>AWB</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Destination</th>
                                <th>Item</th>
                                <th>Weight</th>
                                <th>Date</th>
                                <th>Service</th>
                                @can('mainbranch')
                                <th>Agent</th>
                                @endcan
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipments as $item)
                            <tr>
                                <td>{{$item->awb}}</td>
                                <td>{{$item->shipper->name}}</td>
                                <td>{{$item->receiver->name}}</td>
                                <td>{{$item->receiver->country}}</td>
                                <td>{{Str::limit($item->description, 25)}}</td>
                                <td>{{$item->weight}}KG</td>
                                <td>{{$item->date->format('d M Y')}}</td>
                                <td>{{$item->service->name}}</td>
                                @can('mainbranch')
                                <td>{{$item->agent->name}}</td>
                                @endcan
                                <td>
                                    <a href="{{route('admin.shipments.show', $item->id)}}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="card-footer">
                    {{$shipments->links()}}
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
            format: 'MMMM D, YYYY'
        });

        $('#datepicker').datepicker({
            autoclose: true,
            todayBtn: "linked"
        });


    });
</script>
@endsection