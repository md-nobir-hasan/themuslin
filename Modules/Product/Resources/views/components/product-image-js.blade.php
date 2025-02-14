<script>
    // write a script for submitting product image change
    $(document).on("click", ".change-product-image-button", function (e) {
        $(this).closest("form.change-product-image-form").trigger('submit');
    });

    // write a script for setup product id inside product-image modal
    $(document).on("click", ".product-image-change-action-button", function (e) {
        $(".change-product-image-form .product-id").val($(this).attr("data-product-id"));
    });

    // now add an event listener for listen form submit
    $(document).on("submit", ".change-product-image-form", function (e) {
        // this line will stop page loading
        e.preventDefault();
        const productImage = $(this).find('input[name=image_id]');
        const productRow = $(`tr[data-product-id-row=${$(this).find('.product-id').val()}]`);

        let formData = new FormData(e.target);

        send_ajax_request("POST", formData, "{{ $route ?? route('admin.products.update-image') }}", function () {
        }, (response) => {
            // does success action
            ajax_toastr_success_message(response)
            // hide this modal change product image in product table

            productRow.find('.image-box').html(`<img alt="" src="${productImage.attr('data-imgsrc')}" class="">`);
        }, (errors) => prepare_errors(errors))
    })
</script>