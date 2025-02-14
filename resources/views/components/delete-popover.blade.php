@php
    $type = $type ?? '';
@endphp

@if ($type == '')
    <a tabindex="0" class="btn btn-sm btn-danger btn-xs mb-2 me-1 swal_delete_button">
        <i class="ti-trash"></i>
    </a>
@else
    <a class="dropdown-item swal_delete_button"><i class="ti-trash"></i> {{ __('Delete') }} </a>
@endif


<form method='post' action='{{ $url }}' class="d-none">
    @if ($type != '')
        @method('DELETE')
        @csrf
    @endif

    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
    <br>
    <button type="submit" class="swal_form_submit_btn btn-sm d-none"></button>
</form>
