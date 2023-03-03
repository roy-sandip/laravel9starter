@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Make new rate chart</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                 <div class="row">
                     <div class="col-md-4 offset-md-4">
                          <form action="{{route('admin.rates.store')}}" method="POST">
                              @csrf
                              <div class="form-group required">
                                  <label for="">Rate Name</label>
                                  <input type="text" name="name" class="form-control" value="{{old('name')}}" required>
                              </div>

                              <div class="form-group">
                                <label for="">Company</label>    
                                <select name="company" id="" class="form-control select2" required>
                                    <option disabled selected>Select Company</option>
                                    @foreach($companies as $item)
                                    <option value="{{$item->id}}" @if(old('company') == $item->id) selected @endif >{{$item->name}}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="form-group">
                                  <label for="">Zone</label>
                                  <select name="zone_scheme" id="" class="form-control">
                                    <option disabled selected>Select Zone Scheme</option>
                                    @foreach($zone_schemes as $item)
                                    <option value="{{$item->id}}" @if(old('zone_scheme') == $item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="">Fuel Charge</label>
                                  <div class="input-group">
                                  <input type="number" name="fuel_charge" class="form-control numeric" value="0" min="0" max="100">
                                      <div class="input-group-append">
                                          <div class="input-group-text">%</div>
                                      </div>
                                  </div>
                              </div>

                              
                              <div class="form-group">
                                  <button class="btn btn-primary" type="submit">Submit</button>
                              </div>
                          </form>
                     </div>
                 </div>
                </div>
                <div class="card-footer text-center">
                   
                </div>
            </div>
        </div>
    </div>




@endsection