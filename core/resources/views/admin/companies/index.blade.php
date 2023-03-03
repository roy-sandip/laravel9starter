@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Tracking Companies</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        
                        <button class="btn btn-tool" type="button" id="sync-service">
                            <i class="fas fa-sync"></i>
                        </button>
                        
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <x-message/>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Key</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->key}}</td>
                                <td>{{$item->url}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$companies->links()}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#sync-service').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{route('admin.companies.sync')}}",
                type: 'get',
                dataType: 'json',
                beforeSend: function(){
                    $("#sync-service i").addClass('fa-spin');
                },
                complete: function(){
                    $("#sync-service i").removeClass('fa-spin');
                },
                success: function(response){
                    toastr.success('Companies synced from Binary IT Lab server. Refresh to see changes.');
                },
                error: function(error){
                    toastr.error('Failed to sync. Contact your developer.');
                    console.log(error);
                }
            });


        });
    });
</script>
@endsection