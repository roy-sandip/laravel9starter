@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Service # {{$service->id}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Detail</h5>

                </div>
                <div class="card-body row">
                    <div class="col-md-4 offset-md-4 table-responsive">
                    <x-message/>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Service ID</td>
                                    <td>{{$service->id}}</td>
                                </tr>
                                <tr>
                                    <td>Service Name</td>
                                    <td>{{$service->name}}</td>
                                </tr>
                                <tr>
                                    <td>Public Name</td>
                                    <td>{{$service->public_name}}</td>
                                </tr>
                                <tr>
                                    <td>Tracking Provider</td>
                                    <td>{{$service->company_key}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="{{route('admin.services.edit', $service->id)}}" class="btn btn-primary btn-sm">
                            Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete">Delete</button>
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
    <form id="confirm-delete-form" action="{{route('admin.services.destroy', $service->id)}}" method="post">
        @method('delete')
        @csrf
        <p class="text-red">You are going to delete the user permenantly.</p>
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