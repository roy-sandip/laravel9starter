
<!-- Modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $title ?? __('Confirm')}}?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <b class="text-red">{{__('Are you sure to delete this record?')}}</b>
        <form action="{{$action}}" method="post" id="form_{{$id}}">
            @method('delete')
            @csrf
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" form="form_{{$id}}" class="btn btn-md btn-danger">{{__('Delete')}}</a>
      </div>
    </div>
  </div>
</div>


