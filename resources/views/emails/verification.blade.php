@extends('layouts.email')


@section('content')

    <table
        bgcolor="#ffffff"
        border="0"
        cellpadding="0"
        cellspacing="0"
        style="
                                margin: 0;
                                padding-right: 20px;
                                padding-left: 20px;
                                padding-bottom: 10px;
                                padding-top: 10px;
                            "
        width="100%"
    >
        <tr>
            <td style="padding-top: 10px; padding-bottom: 20px">
                <span
                    style="
                        display: block;
                        font-family: Arial, sans-sarif;
                        font-size: 16px;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    Здравствуйте.
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span
                    style="
                        display: block;
                        font-family: Arial, sans-sarif;
                        font-size: 16px;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    Пожалуйста, подтвердите адрес своей электронной почты на
                    сайте
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
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span
                    style="
                        display: block;
                        font-family: Arial, sans-sarif;
                        font-size: 16px;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    Введите код в форме:
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span
                    style="
                        display: block;
                        font-family: Arial, sans-sarif;
                        font-size: 16px;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    {{$code}}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span
                    style="
                        display: block;
                        font-family: Arial, sans-sarif;
                        font-size: 16px;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    Или перейдите по ссылке:
                </span>
            </td>
        </tr>
    </table>
    <table
        bgcolor="#ffffff"
        border="0"
        cellpadding="0"
        cellspacing="0"
        style="
                                margin: 0;
                                padding-right: 20px;
                                padding-left: 20px;
                                padding-bottom: 40px;
                                border-bottom: 1px solid #e6e6e6;
                            "
        width="100%"
    >
        <tr>
            <td height="50px">
                <a
                    href="{{$url}}"
                    style="
                                            color: #ffffff;
                                            font: 14px Arial, sans-serif;
                                            line-height: 30px;
                                            display: block;
                                            width: 220px;
                                            padding-top: 5px;
                                            padding-bottom: 5px;
                                            text-decoration: none;
                                            font-weight: bold;
                                            text-align: center;
                                            background: linear-gradient(
                                                91.41deg,
                                                #2575ed 0%,
                                                #50b987 100%
                                            );
                                        "
                    target="_blank"
                >
                    Подтвердить адрес
                </a>
            </td>
        </tr>
    </table>

@stop
