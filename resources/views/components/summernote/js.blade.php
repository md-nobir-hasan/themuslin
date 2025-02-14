
<script src="{{asset('assets/js/summernote-lite.min.js')}}"></script>
<script>
    const summernoteConfig = {
        disableDragAndDrop: true,
        height: 300, //set editable area's height
        codeviewFilter: true,
        codeviewIframeFilter: true,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['Insert', ['link', 'table', 'video', 'picture']],
            ['view', ['codeview']],
        ],
        styleTags: [
            'p',
            {
                title: 'Blockquote',
                tag: 'blockquote',
                className: 'blockquote',
                value: 'blockquote'
            },
            'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ],
        codemirror: { // codemirror options
            theme: 'monokai',
            mode: 'text/html',
            htmlMode: true,
            lineNumbers: true
        },
        callbacks: {
            onPaste: function(e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window
                    .clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    };

    $(document).ready(function () {
        let summerNote = $('.summernote');
        summernoteInit(summerNote);
    });

</script>