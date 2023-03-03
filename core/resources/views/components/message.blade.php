@if(count($errors->all()) > 0)
<div class="content">
    <div class="alert alert-danger   alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@if(Session::has('success'))
<div class="content">
    <div class="alert alert-success alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="alert-heading font-size-h4 font-w400">Successfull!</h3>
        <p class="mb-0">{{Session::get('success')}}</p>
    </div>
    </div>
@endif

@if(Session::has('status'))
<div class="content">
    <div class="alert alert-success alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="alert-heading font-size-h4 font-w400">Status</h3>
        <p class="mb-0">{{Session::get('status')}}</p>
    </div>
</div>
@endif

@if(Session::has('message'))
<div class="content">
    <div class="alert alert-info alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="alert-heading font-size-h4 font-w400">Notice</h3>
        <p class="mb-0">{{Session::get('message')}}</p>
    </div>
    </div>
@endif



@if(Session::has('error'))
<div class="content">
<div class="alert alert-danger alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Error</h3>
    <p class="mb-0">{{Session::get('error')}}</p>
</div>
</div>
@endif