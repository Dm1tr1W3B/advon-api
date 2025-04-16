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
                        font-weight: bold;
                        text-align: left;
                        padding-top: 10px;
                    "
                >
                    Новое объявление от {{$author->name}}
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
                    Автор, на которого вы подписаны -
                    <a

                        @if(!empty($advertisement->company_id))
                            href="{{ env('FRONT_URL') }}/company-detail/{{$author->id}}"
                        @else
                            href="{{ env('FRONT_URL') }}/user-detail/{{$author->id}}"
                        @endif

                        style="
                            color: #2575ed;
                            font: 16px Arial, sans-serif;
                            line-height: 30px;
                            text-decoration: none;
                            font-weight: bold;
                        "
                        target="_blank"
                    >
                        {{$author->name}}
                    </a>
                    - выложил новое объявление.
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 25px">
                    <span
                        style="
                            padding-right: 30px;
                            display: inline-block;
                            height: 158px;
                            vertical-align: top;
                        "
                    >
                        <img
                            width="200"
                            height="158"
                            src="{{$image}}"
                            alt=""
                            titel=""
                        />
                    </span>
                <span
                    style="
                                            display: inline-block;
                                            height: 158px;
                                            vertical-align: top;
                                            padding-top: 25px;
                                        "
                >
                    <span
                        style="
                            display: inline-block;
                            font-size: 20px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        "
                    >{{$advertisement->title}}</span
                    >
                    <span
                        style="
                            display: block;
                            font-size: 16px;
                            margin-bottom: 10px;
                        "
                    >Посмотреть детально на сайте
                        <a
                            href="{{ env('FRONT_URL') }}/product/{{$advertisement->id}}"
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
                        :
                    </span>
                    <a
                        href="{{ env('FRONT_URL') }}/product/{{$advertisement->id}}"
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
                        Смотреть объявление
                    </a>
                </span>
            </td>
        </tr>
    </table>
@stop
