@extends('layouts.admin')

@section('content_header')
    <h1 class="m-0 text-dark">Agent Billings</h1>
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
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Last Invoice</th>
                                <th>Bill</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agents as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->company}}</td>
                                <td>{{$item->lastInvoice->id}}</td>
                                <td>{{$item->lastInvoice->getBill()}}</td>
                                <td>{{$item->lastInvoice->getPaid()}}</td>
                                <td>{{$item->lastInvoice->getDue()}}</td>
                                <td>
                                    <a href="{{route('admin.invoices.index', $item->id)}}" class="btn btn-primary btn-sm">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    {{$agents->links()}}
                </div>
            </div>
        </div>
    </div>


@endsection