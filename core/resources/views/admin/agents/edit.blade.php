@extends('layouts.admin')


@section('content_header')
    <h1 class="m-0 text-dark">Edit Agent # {{$agent->id}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                 <div class="card-header">
                    <h5 class="card-title">Agent Information</h5>
                    <!-- <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-link">#3</a>
                        <a href="#" class="btn btn-tool">
                            <i class="fas fa-pen"></i>
                        </a>
                    </div> -->
                </div>
                <div class="card-body">
                    <x-message/>
                    <div class="row">
                        <div class="col-md-6">
                          <form method="POST" action="{{route('admin.agents.update', $agent->id)}}">
                            @method('put')
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name', $agent->name)}}" required>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Company')}}</label>
                                <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" value="{{old('company', $agent->company)}}" >
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Phone')}}</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{old('phone', $agent->phone)}}" >
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Email')}}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email', $agent->email)}}" >
                            </div>

                            <div class="form-group">
                                <label for="">{{__('Address')}}</label>
                                <textarea name="address" cols="30" rows="3" class="form-control @error('address') is-invalid @enderror">{{old('address', $agent->address)}}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <button class="btn btn-default" type="reset">Clear</button>
                            </div>
                        </form>  
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@stop