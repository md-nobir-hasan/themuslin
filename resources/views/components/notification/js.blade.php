<script>
    $(document).on("click", "#top-bar-notification-icon", function (){
        send_ajax_request("GET",null,"{{ route("update-notification") }}", () => {}, (data) => {
            $("#top-bar-notification-count").text(0);
        }, (errors) => {
            prepare_errors(errors)
        })
    });
</script>