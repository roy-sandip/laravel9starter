@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Agent # {{$agent->id}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                 <x-message/>
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="card-title">Agent Information</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-stripped">
                                    <tr>
                                        <td>ID</td>
                                        <td>{{$agent->id}}</td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{$agent->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Company</td>
                                        <td>{{$agent->company}}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{$agent->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{$agent->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{$agent->address}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 table-responsive">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="card-title">Agent Users</h5>
                            </div>
                            <div class="card-body table-responsive">
                                 <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Userid</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agent->users as $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->username}}</td>
                                            <td>{{$item->role}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>  
                            </div>
                        </div>
                     
                    </div>
                </div>
                    
                    <div class="card-body row">
                        <div class="col text-right">
                            <a href="{{route('admin.agents.edit', $agent->id)}}" class="btn btn-primary btn sm">Edit</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete">Delete</button>
                        </div>
                    </div>

            </div>
        </div>
    </div>



<div class="modal fade" id="modal-delete" style="display: none;" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Are you sure?</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 <span aria-hidden="true">×</span>
</button>
</div>
<div class="modal-body">
    <form id="confirm-delete-form" action="{{route('admin.agents.destroy', $agent->id)}}" method="post">
        @method('delete')
        @csrf
        <p class="text-red">You are going to delete the record permenantly.</p>
    </form>
</div>
<div class="modal-footer justify-content-between">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-danger" onclick="$('#confirm-delete-form').submit()">
    <i class="fa fa-trash-alt"></i>
    Confirm
</button>
</div>
</div>

</div>

</div>



@stop