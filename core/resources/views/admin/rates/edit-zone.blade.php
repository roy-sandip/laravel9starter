@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Edit Zones of {{$zone->name}}</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
               
                <div class="card-body table-responsive">
                 <x-message/>
                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <form action="{{route('admin.rates.store-zone', $zone->id)}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">Zone Name</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name', $zone->name)}}" required>
                                </div>

                                <div class="form-group">
                                    <label for="">Add Countries</label>
                                    <select name="countries[]" multiple="true" id="" class="form-control select2">
                                        @foreach($available_countries as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
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


@section('js')
<script>
    
$(".select2").select2();
</script>
@endsection