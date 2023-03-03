@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">Add New DHL Bill</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        
                </div>
                <div class="card-body table-responsive">
                 <x-message/>

                  <form action="{{route('admin.dhl.store')}}" method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data" >
                      @csrf
                      <div class="form-group">
                          <label for="">Bill No</label>
                          <input type="text" name="bill_no"  class="form-control" max="190" value="{{old('bill_no')}}" required>
                      </div>

                      <div class="form-group">
                          <label for="">Bill Date</label>
                          <input type="text" name="bill_date"  value="{{old('bill_date')}}" class="form-control datepicker" max="190" required>
                      </div>

                      <div class="form-group">
                          <label for="">Total Bill</label>
                          <input type="number" name="total_bill"  value="{{old('total_bill')}}" pattern="[0-9]" inputmode="numeric"  class="form-control numeric" min="0" required>
                      </div>
                      <div class="form-group">
                          <label for="">Comment</label>
                          <textarea name="comment" id="" cols="30" rows="5" class="form-control">{{old('comment')}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="">Attachments (PDF/Image)</label>
                          <input type="file" name="attachment[]" class="form-control" multiple accept=".pdf,image/*">
                      </div>
                      <div class="form-group">
                          <button class="btn btn-primary" type="submit">Submit</button>
                      </div>
                  </form>
                </div>
                <div class="card-footer text-center">
                   
                </div>
            </div>
        </div>
    </div>




@endsection

@section('js')
<script>
    $('.datepicker').datepicker({
        format: "dd MM yyyy",
        endDate: "now",
        maxViewMode: 2,
        todayBtn: "linked",
        autoclose: true
    });
</script>
@endsection