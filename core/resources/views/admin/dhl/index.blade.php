@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">DHL Bills</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active" href="{{route('admin.dhl.create')}}">Add Bill</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bill No.</th>
                                <th>Bill Date</th>
                                <th>Bill Amount</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bills as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->bill_no}}</td>
                                <td>{{$item->bill_date->format('d F Y')}}</td>
                                <td>{{$item->total_bill}}</td>
                                <td></td>
                                <td>
                                    <a href="{{route('admin.dhl.show', $item->id)}}" class="btn btn-primary btn-sm">Show</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                   {{$bills->links()}}
                </div>
            </div>
        </div>
    </div>




@endsection