{{--<script>--}}
    $('.category_filter_section .category_item').on('click', function () {
        $('.category_filter_section .category_item').removeClass('active');
        $(this).addClass('active');
        let category_id = $(this).data('catid');
        let item_count = $(this).closest('.category_filter_section').data('items');
        $('.lds-ellipsis').show();
        $.ajax({
            url: '{{ route('frontend.products.filter.category') }}',
            type: 'GET',
            data: {id: category_id, item_count: item_count},
            success: function (data) {
                $('.lds-ellipsis').hide();
                $('.category_filter_section_product_container').html(data);
            },
            error: function (error) {
                $('.lds-ellipsis').hide();
            }
        });
    });
{{--</script>--}}
