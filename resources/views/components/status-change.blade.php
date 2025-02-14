<a tabindex="0" class="btn btn-success btn-xs mr-1 swal_status_change">
{{--    <i class="mdi mdi-swap-horizontal"></i>--}}
    <i class="las la-sync"></i>
</a>
<form method='post' action='{{$url}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>
