<script>
        $(document).ready(function(){
            function converToSlug(slug){
                let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                finalSlug = slug.replace(/  +/g, ' ');
                finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                return finalSlug;
            }

            //Permalink Code
            $('.permalink_label').addClass('d-none');
            $(document).on('keyup', '.title', function (e) {
                var slug = converToSlug($(this).val());
                var url = `{{url('/')}}/`+ '{{ $module }}' + '/' + slug;

                $('.permalink_top_part').removeClass('d-none');
                $('.permalink_top_url_class').text(url).css('color', 'blue');
                $('.permalink_bottom_blog_slug_input_field').val(slug);
            });

            //Slug Edit Code
            $(document).on('click', '.slug_edit_button', function (e) {
                e.preventDefault();
                $('.permalink_bottom_part').removeClass('d-none');
            });

            //Slug Update Code
            $(document).on('click', '.slug_update_button', function (e) {
                e.preventDefault();
                $('.permalink_bottom_part').addClass('d-none');

                var update_input = $('.permalink_bottom_blog_slug_input_field').val();
                var slug = converToSlug(update_input);
                var url = `{{url('/')}}/`+ '{{ $module }}' + '/' + slug;
                $('.permalink_top_url_class').text(url);

                $('.permalink_top_part').removeClass('d-none');
            });

        });

</script>
