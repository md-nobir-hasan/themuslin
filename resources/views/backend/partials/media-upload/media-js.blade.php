<script>
    ;(function () {
        "use strict";

        $(document).ready(function (){
        let mainUploadBtn = '';

        //after select image
        $(document).on('click','.media_upload_modal_submit_btn',function (e) {
            e.preventDefault();
            let allData = $('.media-uploader-image-list li.selected');
            if( typeof allData != 'undefined'){
                mainUploadBtn.parent().find('.img-wrap').html('');
                let imageId = '';
                $.each(allData,function(index,value){
                    let el = $(this).data();
                    let separator = allData.length == index ? '' : '|';
                    imageId += el.imgid + separator;
                    mainUploadBtn.prev('input').attr('data-imgsrc',el.imgsrc);
                    mainUploadBtn.parent().find('.img-wrap').append('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img src="'+el.imgsrc+'"></div></div></div>');
                });
                 mainUploadBtn.prev('input').val(imageId.substring(0,imageId.length -1));

            }
            $('#media_upload_modal').modal('hide');
            $('.media_upload_form_btn').text('Change Image');
        });


        @can('media-upload-delete')
            //delete image form media uploader
            $(document).on('click','.media_library_image_delete_btn',function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.upload.media.file.delete')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        img_id : $('.image_id').text()
                    },
                    success: function (data) {
                        $('.media-uploader-image-info a,.media-uploader-image-info .img-meta').hide();
                        $('.media-uploader-image-list li.selected').remove();
                        $('.media-uploader-image-info .img-wrapper img').attr('src','');
                        $('.media-uploader-image-info .img-info .img-title').text('');
                    },
                    error: function (error) {

                    }
                });
            });
        @endcan

        $(document).on('click','.media-upload-btn-wrapper .img-wrap .rmv-span',function (e) {
            //imlement remove image icon
            let el = $(this);

            el.parent().parent().find('input[type="hidden"]').val('');
            el.parent().parent().find('.attachment-preview').html('');
            el.parent().parent().find('.media_upload_form_btn').attr('data-imgid','');
            el.hide();
        })

        $(document).on('click','.media_upload_form_btn',function (e) {
            e.preventDefault();
            let parent = $('#media_upload_modal');
            let el = $(this);
            let imageId = el.prev('input').val();
            mainUploadBtn = el;
            let loadAllImage = $('#load_all_media_images');

            parent.find('.media_upload_modal_submit_btn').text(el.data('btntitle'));
            parent.find('.modal-title').text(el.data('modaltitle'));

            if(el.data('mulitple')){
                parent.attr('data-mulitple','true')
            }else{
                parent.removeAttr('data-mulitple');
            }
            loadAllImage.attr('data-selectedimage','');
            if(imageId =! ''){
                loadAllImage.attr('data-selectedimage',el.prev('input').val());
                loadAllImage.trigger('click');
            }
        });

        $('body').on('click', '.media-uploader-image-list > li', function (e) {
            e.preventDefault();
            let el = $(this);
            let allData = el.data();

            if( typeof $('#media_upload_modal').attr('data-mulitple') == 'undefined'){
                el.toggleClass('selected').siblings().removeClass('selected');
            }else{
                el.toggleClass('selected');
            }

            $('.media-uploader-image-info a,.media-uploader-image-info .img-meta,.delete_image_form').show();

            let parent = $('.img-meta');
            parent.children('.date').text(allData.date);
            parent.children('.dimension').text(allData.dimension);
            parent.children('.size').text(allData.size);
            parent.children('.imgsrc').text(allData.imgsrc);
            parent.children('.image_id').text(allData.imgid);
            parent.find('input[name="img_alt_tag"]').val(allData.alt);
            parent.parent().find('input[name="img_id"]').val(allData.imgid);

            $('.img_alt_submit_btn').html('<i class="las la-check"></i>');
            $('.img-info .img-title').text(allData.title)
            $('.media-uploader-image-info .img-wrapper img').attr('src',allData.imgsrc);
        });

        Dropzone.options.placeholderfForm = {
            dictDefaultMessage: "{{__('Drag or Select Your Image')}}",
            maxFiles: 50,
            maxFilesize: 10, //MB
            acceptedFiles: 'image/*',
            success: function (file, response) {
                if (file.previewElement) {
                    return file.previewElement.classList.add("dz-success");
                }
                $('#load_all_media_images').trigger('click');
                $('.media-uploader-image-list li:first-child').addClass('selected');
            },
            error: function (file, message) {
                if (file.previewElement) {
                    file.previewElement.classList.add("dz-error");
                    if ((typeof message !== "String") && message.error) {
                        message = message.error;
                    }
                    for (let node of file.previewElement.querySelectorAll("[data-dz-errormessage]")) {
                        node.textContent = message.errors.file[0];
                    }
                }
            }
        };


        $(document).on('click', '#upload_media_image', function (e) {
            e.preventDefault();
            $('.media_upload_modal_submit_btn').hide();
        });

        @can('media-upload-loadmore')
            $(document).on('click', '#load_all_media_images', function (e) {
                e.preventDefault();
                loadAllImages();
            });
        @endcan

        @can('media-upload-alt')
            $(document).on('click', '.img_alt_submit_btn', function (e) {
                e.preventDefault();
                //admin.upload.media.file.alt.change
                let parent = $(this).parent().parent().parent();
                let alt = $(this).prev('input').val();
                let imgId = parent.find('.image_id').text();

                $.ajax({
                    type: "POST",
                    url: "{{route('admin.upload.media.file.alt.change')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        imgid: parseInt(imgId),
                        alt: alt
                    },
                    success: function (data) {
                        $('.img_alt_submit_btn').html('<i class="las la-check-circle"></i>');
                        $('.media-uploader-image-list li[data-imgid="'+imgId+'"]').data('alt',alt);
                    }
                });
            });
        @endcan

        function loadAllImages() {
            let selectedImage = $('#load_all_media_images').attr('data-selectedimage');
            $.ajax({
                type: "POST",
                url: "{{route('admin.upload.media.file.all')}}",
                data: {
                    _token: "{{csrf_token()}}",
                    'selected' : selectedImage
                },
                success: function (data) {
                    $('.media-uploader-image-list').html('');
                    $.each(data,function (index,value) {

                        $('.media-uploader-image-list').append('<li data-date="'+value.upload_at+'" data-imgid="'+value.image_id+'" data-imgsrc="'+value.img_url+'" data-size="'+value.size+'" data-dimension="'+value.dimensions+'" data-title="'+value.title+'" data-alt="'+value.alt+'">\n' +
                            '<div class="attachment-preview">\n' +
                            '<div class="thumbnail">\n' +
                            '<div class="centered">\n' +
                            '<img src="'+value.img_url+'" alt="">\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '</li>');

                    });
                    hidePreloader();
                    $('.media_upload_modal_submit_btn').show();
                    selectOldImage();
                    $('#loadmorewrap button').show();
                },
                error: function (error) {

                }
            });
        }


        /**
         * hide preloader
         * @since 2.2
         * */
        function hidePreloader() {
            $('.image-preloader-wrapper').hide(300);
        }

        /**
         * Select preveiously selected image
         * @since 2.2
         * */
        function selectOldImage(){
            let imageId = mainUploadBtn.prev('input').val();
            let matches = imageId.match(/([|])/g);
            if(matches != null){
                let imgArr = imageId.split('|');
                let filtered = imgArr.filter(function (el) {
                    return el != "";
                });
                $.each(filtered,function(index,value){
                    $('.media-uploader-image-list li[data-imgid="'+value+'"]').trigger('click');
                });
            }else{
                $('.media-uploader-image-list li[data-imgid="'+imageId+'"]').trigger('click').siblings().removeClass('selected');
            }

        }

        /* loadmore image  */
            $(document).on('click','#loadmorewrap',function (){
                let mediaImageWrapper = $('#media_library');
                let skipp = mediaImageWrapper.find('ul.media-uploader-image-list li').length - 1;
                $('#loadmorewrap button').append(' <i class="fas fa-spinner fa-spin"></i>');
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.upload.media.file.loadmore')}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        'skip' : skipp
                    },
                    success: function (data) {
                        $.each(data,function (index,value) {
                            mediaImageWrapper.find('.media-uploader-image-list').append(
                                '<li data-date="'+value.upload_at+'" data-imgid="'+value.image_id+'" data-imgsrc="'+value.img_url+'" data-size="'+value.size+'" data-dimension="'+value.dimensions+'" data-title="'+value.title+'" data-alt="'+value.alt+'">\n' +
                                    '<div class="attachment-preview">\n' +
                                        '<div class="thumbnail">\n' +
                                            '<div class="centered">\n' +
                                                '<img src="'+value.img_url+'" alt="">\n' +
                                            '</div>\n' +
                                        '</div>\n' +
                                    '</div>\n' +
                                '</li>'
                            );
                        });
                        if(data == ''){
                            $('#loadmorewrap button').hide();
                        }
                        $('#loadmorewrap button i').remove();
                    },
                    error: function (error) {

                    }
                });
            });
        });
    })();
</script>
