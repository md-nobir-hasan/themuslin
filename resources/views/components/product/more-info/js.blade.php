<script>
    (function ($) {
        "use strict"
        $(document).ready(function () {
            $(document).on('click', '.add_more_info_btn',function () {
                $(this).closest('.additional_info').append(`<x-product.more-info.repeater />`);
            });

            $(document).on('click', '.remove_this_info_btn',function () {
                $(this).closest('.additional_info_repeater').remove();
            });
        });
    })(jQuery);
</script>
