<a tabindex="0" class="btn dropdown-item status_dropdown__list__link swal_delete_button">{{ $title }}</a>
<form method='post' action='{{$url}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>
