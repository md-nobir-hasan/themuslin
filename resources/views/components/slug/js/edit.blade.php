<script>

    function converToSlug(slug){
        let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
        finalSlug = slug.replace(/  +/g, ' ');
        finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
        return finalSlug;
    }

    //Permalink Code
    var sl =  $('.permalink_bottom_blog_slug_input_field').val();
    var url = `{{url('/')}}/` + '{{$module}}' + '/' + sl;
    $('.permalink_top_url_class').text(url).css('color', 'blue');


    //Slug Edit Code
    $(document).on('click', '.slug_edit_button', function (e) {
        e.preventDefault();
        $('.permalink_bottom_part').removeClass('d-none');
    });

    //Slug Update Code
    $(document).on('click', '.slug_update_button', function (e) {
        e.preventDefault();
        var update_input = $('.permalink_bottom_blog_slug_input_field').val();
        var slug = converToSlug(update_input);
        var url = `{{url('/')}}/` + '{{$module}}' + '/' + slug;
        $('.permalink_top_url_class').text(url);
        $('.permalink_bottom_part').addClass('d-none');
        $('.permalink_top_part').removeClass('d-none');
    });


    $(document).on('keyup','.permalink_bottom_blog_slug_input_field',function(){

        var slug = converToSlug($(this).val());
        var url = `{{url('/')}}/`+ '{{ $module }}' + '/' + slug;
        $('.permalink_top_url_class').text(url);
    });

</script>
