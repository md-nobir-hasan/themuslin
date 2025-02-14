<!doctype html>
<html lang="{{get_default_language()}}" dir="{{get_default_language_direction()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ get_static_option('site_title').' '. __('Mail') }}</title>
    <style>
        * {
            font-family: 'Open Sans', sans-serif;
        }

        .mail-container {
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
            background-color: #f2f2f2;
            padding: 40px 0;
        }

        .logo-wrapper img {
            max-width: 200px;
        }

        .inner-wrap {
            background-color: #fff;
            margin: 40px;
            padding: 30px 20px;
            text-align: left;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.01);
        }

        .inner-wrap p {
            font-size: 16px;
            line-height: 26px;
            color: #656565;
            margin: 0;
        }
    </style>
</head>
<body>
<main>
    <div class="mail-container">
        <div class="logo-wrapper">
            <a href="{{url('/')}}">
                {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
            </a>
        </div>
        <div class="inner-wrap">
            {!! $mail_message !!}
        </div>
    </div>
</main>
<footer>
    {!! get_footer_copyright_text() !!}
</footer>
</body>
</html>
