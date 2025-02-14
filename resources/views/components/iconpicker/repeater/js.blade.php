@php
   $selector = $selector ?? '.social_links .social_link .add';
@endphp
<script>
(function (){
    "use strict";

    $(document).ready(function () {
        $(document).on('click', '{{ $selector }}', function () {
            $(this).closest('.social_links').append(
                `<div class="social_link row">
                    <div class="col-sm-1 col-xl-1">
                        <x-iconpicker.input :id="'social_icon'" :name="'social_icon[]'" :label="''"/>
                    </div>
                    <div class="col-sm-8 col-xl-8 form-group ml-6">
                        <input type="text" class="form-control" id="social_link"  name="social_link[]" placeholder="{{__('Link')}}">
                    </div>
                    <div class="col-sm-3 col-xl-3">
                        <button type="button" class="btn btn-sm btn-success add">+</button>
                        <button type="button" class="btn btn-sm btn-danger remove">-</button>
                    </div>
                </div>`
            );
        });

        $(document).on('click', '.social_links .social_link .remove', function () {
            $(this).closest('.social_link').remove();
        });
    });
})(jQuery);
</script>
