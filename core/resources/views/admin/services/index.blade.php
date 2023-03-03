@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Services</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active" href="{{route('admin.services.create')}}">Create</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-body table-responsive">
                    <x-message/>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Public Name</th>
                                <th>Tracking Provider</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->public_name}}</td>
                                <td>{{$item->company_key}}</td>
                                <td>
                                    <a href="{{route('admin.services.show', $item->id)}}" class="btn btn-primary btn-sm">Show</a>
                                    <a href="{{route('admin.services.edit', $item->id)}}" class="btn btn-warning btn-sm">Edit</a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop