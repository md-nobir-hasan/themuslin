@php
    $url = ($type ?? 'admin') === "vendor" ? route('vendor.products.update.status') : route('admin.products.update.status');
@endphp

<script>
    /*
    ========================================
        Click add Value text
    ========================================
    */

    $(document).on('click', '.status-dropdown .single-item', function(event) {
        let el = $(this);
        let value = el.data('value');
        let parentWrap = el.parent().parent();
        parentWrap.find('.add-dropdown-text').text(value);
        parentWrap.find('.add-dropdown-text').attr('value', value);
        return true;
    });

    $(document).on("click",".status-dropdown .dropdown-menu li",function (){
        let id = $(this).attr("data-id");
        let statusId = $(this).attr("data-status-id");

        let data = new FormData();
        data.append("id", id);
        data.append("status_id", statusId);
        data.append("_token", "{{ csrf_token() }}");

        send_ajax_request("post", data, "{{ $url }}", function () {
            toastr.success("Request sent..");
        }, function (data) {
            ajax_toastr_success_message(data);
        }, function () {
            ajax_toastr_error_message(xhr);
        });
    })
</script>
