@extends('layouts.admin')
@section('content_header')
    <h1 class="m-0 text-dark">DHL Bill # {{$bill->bill_no}}</h1>
@stop
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                 <x-message/>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <table class="table table-stripped table-bordered">
                                <tr>
                                    <td>DHL Bill No.</td>
                                    <td>{{$bill->bill_no}}</td>
                                </tr>

                                <tr>
                                    <td>Bill Date</td>
                                    <td>{{$bill->bill_date->format('d M Y')}}</td>
                                </tr>
                                <tr>
                                    <td>Total Bill</td>
                                    <td>{{ number_format($bill->total_bill)}}</td>
                                </tr>

                                <tr>
                                    <td>Comment</td>
                                    <td>{{$bill->comment}}</td>
                                </tr>
                                <tr>
                                    <td>Attachments</td>
                                    <td>
                                        <table class="table table-hover">
                                            @foreach($bill->attachments as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->created_at->format('d/m/Y')}}</td>
                                                <td>
                                                    <a href="{{route('admin.media.download', $item->id)}}">Download</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Transactions</td>
                                    <td>
                                        <table class="table table-stripped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Comment</th>
                                                    <th>Attachment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($bill->transactions as $item)
                                                <tr>
                                                    <td>{{$item->datetime->format('d M Y')}}</td>
                                                    <td>{{number_format($item->amount)}}</td>
                                                    <td>{{$item->comment}}</td>
                                                    <td>
                                                        @if($item->attachment)
                                                        <a href="{{$item->attachment->link()}}">Download</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPayment">
  Add Payment
</button>
                   <a href="{{route('admin.pdf.dhl-statement', $bill->id)}}" target="_blank" class="btn btn-secondary">Print</a>
                </div>
            </div>
        </div>
    </div>




<!-- Modal -->
<div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Record Payment for bill # {{$bill->bill_no}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.dhl.add-transaction', $bill->id)}}" method="post" id="paymentForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">Amount</label>
                <input type="number" name="amount" min="0" max="999999999" class="form-control numeric" required>
            </div>
            <div class="form-group">
                <label for="">Date</label>
                <input type="text" name="datetime" class="form-control datepicker" required>
            </div>
            <div class="form-group">
                <label for="">Comment</label>
                <input type="text" name="comment" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Attachment</label>
                <input type="file" name="attachment" class="form-control">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="paymentForm" class="btn btn-primary">Submit</button>
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