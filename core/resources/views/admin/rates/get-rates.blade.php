@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Rates</h1>
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
                        <form action="{{route('admin.rates.get')}}" method="post" class="">
                            @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Country</div>
                                </div>
                                <select name="country" id="" class="form-control select2" required>
                                    <option></option>
                                    @foreach($countries as $item)
                                    <option value="{{$item->id}}">{{$item->name}} - {{$item->alpha2}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rate Chart</div>
                                </div>
                                <select name="rate_chart" id="" class="form-control">
                                    <option disabled selected>All Rates</option>
                                    @foreach($rate_charts as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group required">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Weight</div>
                                </div>
                                <input name="weight" type="number" min="0.01" max="999" step="0.01" class="form-control numeric" required placeholder="Weight" maxlength="5">
                                <div class="input-group-append">
                                    <div class="input-group-text">KG</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Type</div>
                                </div>
                                <select name="type" id="" class="form-control">
                                    <option value="SPX" selected>SPX</option>
                                    <option value="DOX">DOC</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-primary" type="submit">Get Rates</button>
                            <button class="btn btn-default" type="reset">Clear</button>
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
  $('.select2').select2({
    placeholder: 'Select Country'
  });
</script>
@endsection