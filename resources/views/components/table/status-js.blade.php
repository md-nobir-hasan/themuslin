<script>
    $(document).on("click",".status-dropdown .dropdown-menu li",function (){
        let id = $(this).attr("data-id");
        let statusId = $(this).attr("data-status-id");

        let data = new FormData();
        data.append("id", id);
        data.append("status_id", statusId);
        data.append("_token", "{{ csrf_token() }}");

        send_ajax_request("post", data, '{{ route('admin.products.update.status') }}', function () {
            toastr.success("Request sent..");
        }, function (data) {
            ajax_toastr_success_message(data);
        }, function () {
            ajax_toastr_error_message(xhr);
        });
    })
</script>