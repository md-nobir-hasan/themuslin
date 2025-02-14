<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px;
        }

        .content h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }

        .divider {
            background-color: #ececec;
            height: 2px;
            margin: 20px 0;
        }

        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #003366;
            margin-bottom: 20px;
            color: #333;
            font-size: 16px;
        }

        .footer {
            background-color: #003366;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
        }

        @media screen and (max-width: 600px) {
            .email-container {
                width: 100%;
                padding: 10px;
            }

            .content h1 {
                font-size: 20px;
            }

            .content p {
                font-size: 14px;
            }

            .message-box {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <center>
        <table role="presentation" class="email-container">
            <tr>
                <td>
                    <div class="header">
                        <a href="{{url('/')}}">
                            <img src="https://themuslinbd.com/assets/muslin/images/static/_logo-black.png" alt="themuslinbdmail">
                        </a>
                    </div>

                    <div class="content">
                        <!-- <h1>Order Status</h1> -->

                        <div class="divider"></div>

                        <div class="message-box">
                            {!! $data['message'] !!}
                        </div>

                        {{-- <footer class="footer">
                            {!! get_footer_copyright_text() !!}
                        </footer> --}}
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
