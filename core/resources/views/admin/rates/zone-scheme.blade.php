@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">{{$zone_scheme->name}}</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                     <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <form action="{{route('admin.rates.add-zone', $zone_scheme->id)}}" class="form-inline" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Add New Zone" value="{{old('name')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Add Zone</button>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped">
                      
                        <tbody>
                          @foreach($zone_scheme->zones as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>
                                    {{$item->countries->pluck('name')->join(', ')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.rates.edit-zone', $item->id)}}" class="btn btn-primary btn-sm">Show</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                   
                </div>
            </div>
        </div>
    </div>




@endsection