<button class="btn btn-{{$color ?? 'primary'}} btn-{{$size ?? 'md'}}" id="{{$id ?? ''}}" type="{{$type ?? 'submit'}}">
<i class="{{$icon ?? ''}}"></i>
{{__($label) ?? __('Submit')}}
</button>