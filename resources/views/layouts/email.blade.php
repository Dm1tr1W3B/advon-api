<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="UTF-8" />
    <title>Title</title>
</head>
<body>
<table
    bgcolor="#f2f2f2"
    border="0"
    cellpadding="0"
    cellspacing="0"
    style="margin: 0; padding: 0"
    width="100%"
>
    <tr height="20px" style="height: 20px"></tr>
    <tr align="center">
        <td height="100%">
            <center style="max-width: 600px; width: 100%">
                <table
                    bgcolor="#ffffff"
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                    style="margin: 0; padding: 0"
                    width="100%"
                >
                    <tr width="100%" align="center">
                        <td
                            style="
                                        padding-top: 12px;
                                        padding-bottom: 12px;
                                        border-bottom: 1px solid #e6e6e6;
                                    "
                        >
                            <img
                                width="140"
                                height="40"
                                src="{{url('/storage/emails/logo.png')}}"
                                alt="logo"
                            />
                        </td>
                    </tr>
                </table>
                @yield('content')
                <table
                    bgcolor="#ffffff"
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                                margin: 0;
                                padding-right: 20px;
                                padding-left: 20px;
                                padding-bottom: 25px;
                                padding-top: 25px;
                                border-bottom: 1px solid #e6e6e6;
                            "
                    width="100%"
                >
                    <tr>
                        <td>С уважением,</td>
                    </tr>
                    <tr>
                        <td>
                            Команда
                            <a
                                href="{{ env('FRONT_URL') }}"
                                style="
                                            color: #2575ed;
                                            font: 16px Arial, sans-serif;
                                            line-height: 30px;
                                            text-decoration: none;
                                            font-weight: bold;
                                        "
                                target="_blank"
                            >
                                ADVon.me
                            </a>
                        </td>
                    </tr>
                </table>
                <table
                    border="0"
                    bgcolor="#EEEEEE"
                    cellpadding="0"
                    cellspacing="0"
                    style="margin: 0; padding-bottom: 20px; padding-top: 30px"
                    width="100%"
                >
                    <tr align="center">
                        <td>
                            <a
                                href="https://www.facebook.com/advon.me"
                                style="
                                            text-decoration: none;
                                            padding-right: 10px;
                                            padding-left: 10px;
                                        "
                                target="_blank"
                            >
                                <img
                                    width="36"
                                    height="36"
                                    src="{{url('/storage/emails/fb.png')}}"
                                    alt="fb"
                                    title="facebook"
                                />
                            </a>
                            <a
                                href="https://vk.com/advon"
                                style="
                                            text-decoration: none;
                                            padding-right: 10px;
                                            padding-left: 10px;
                                        "
                                target="_blank"
                            >
                                <img
                                    width="36"
                                    height="36"
                                    src="{{url('/storage/emails/vk.png')}}"
                                    alt="vk"
                                    title="vk"
                                />
                            </a>
                            <a
                                href="https://www.instagram.com/advon.me/"
                                style="
                                            text-decoration: none;
                                            padding-right: 10px;
                                            padding-left: 10px;
                                        "
                                target="_blank"
                            >
                                <img
                                    width="36"
                                    height="36"
                                    src="{{url('/storage/emails/inst.png')}}"
                                    alt="instagram"
                                    title="instagram"
                                />
                            </a>
                        </td>
                    </tr>
                </table>
                <table
                    border="0"
                    bgcolor="#EEEEEE"
                    cellpadding="0"
                    cellspacing="0"
                    style="margin: 0; padding-bottom: 20px"
                    width="100%"
                >
                    <tr align="center">
                        <td style="font-size: 14px; padding-bottom: 10px">
                            <a
                                href="{{env('FRONT_URL')}}/cooperation"
                                style="
                                            padding-right: 29px;
                                            padding-left: 29px;
                                            color: #9e9e9e;
                                        "
                                target="_blank"
                            >
                                Сотрудничество
                            </a>
                            <a
                                href="{{env('FRONT_URL')}}/contacts"
                                style="
                                            padding-right: 29px;
                                            padding-left: 29px;
                                            color: #9e9e9e;
                                        "
                                target="_blank"
                            >
                                Контакты
                            </a>
                            <a
                                href="{{env('FRONT_URL')}}/legal-information"
                                style="
                                            padding-right: 29px;
                                            padding-left: 29px;
                                            color: #9e9e9e;
                                        "
                                target="_blank"
                            >
                                Юридическая справка
                            </a>
                        </td>
                    </tr>
                    <tr align="center">
                        <td
                            style="font-size: 12px; padding-top: 10px; padding-bottom: 10px"
                        >
                                    <span style="color: #9e9e9e">
                                        Если вы хотите отписаться от рыссылки Advon.me нажмите
                                        <a href="{{url('/api/v1/deleteSubscriptionByEmail/')}}@if(!empty($email))?email={{$email}}" @endif style="color: #9e9e9e" target="_blank">
                                            здесь
                                        </a>
                                    </span>
                        </td>
                    </tr>
                    <tr align="center">
                        <td style="font-size: 12px">
                                    <span style="color: #9e9e9e">
                                        2021 - ADVon.me - все права защищены ©. Свидетельство
                                        №2019613383
                                    </span>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <tr height="20px" style="height: 20px"></tr>
</table>
</body>
</html>
