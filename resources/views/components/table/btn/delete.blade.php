<a tabindex="0" class="btn btn-danger btn-xs mb-2 me-1" role="button" data-bs-toggle="popover" data-trigger="focus"
    data-html="true" title=""
    data-content="
        <h6>{{ __('Are you sure to delete this?') }}</h6>
        <form method='post' action='{{ $route }}'>
        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
        <br>
        <input type='submit' class='btn btn-danger btn-xs' value='{{ __('Yes, Please') }}'>
        </form>
">
    <i class="ti-trash"></i>
</a>
