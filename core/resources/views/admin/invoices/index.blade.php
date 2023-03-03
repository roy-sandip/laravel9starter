@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Agent # {{$agent->id}}</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <form action="{{route('admin.invoices.create', $agent->id)}}" method="GET" class="form form-inline">
                           
                            <div class="form-group">
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


                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">New Bill</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Period</th>
                                <th>Bill</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $item)
                            <tr class="@if(!$item->locked()) table-info @endif">
                                <td>{{$item->id}}</td>
                                <td>{{$item->created_at->format('d M Y')}}</td>
                                <td>{{$item->from->format('d M')}} - {{$item->to->format('d M Y')}}</td>
                                
                                <td>{{$item->getBill()}}</td>
                                <td>{{$item->getPaid()}}</td>
                                <td>{{$item->getBalance()}}</td>
                                <td>
                                    <a href="{{route('admin.invoices.show', $item->id)}}" class="btn btn-primary btn-sm">Show</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                   {{$invoices->links()}}
                </div>
            </div>
        </div>
    </div>




@endsection


@section('js')
<script>

    $('#datepicker').datepicker({
    format: "dd/mm/yyyy",
    endDate: "now",
    maxViewMode: 2,
    todayBtn: "linked",
    autoclose: true

});
</script>
@endsection