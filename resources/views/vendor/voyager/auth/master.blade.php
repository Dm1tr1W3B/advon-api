<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>@yield('title', 'Admin - '.Voyager::setting("admin.title"))</title>
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    @if (__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif
    <style>


        body.login .login-sidebar {
            border-top: 5px solid{{ config('voyager.primary_color','#22A7F0') }};

        }
        .container-small {
            width: 500px;
        }

        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top: 0px !important;
                border-left: 5px solid{{ config('voyager.primary_color','#22A7F0') }};
            }
        }

        body.login .form-group-default.focused {
            border-color: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .login-button, .bar:before, .bar:after {
            background: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .remember-me-text {
            padding: 0 5px;
        }

        body, html {
            height: 100%;
            display: grid;
        }

        .center-me {
            margin: auto;
        }
    </style>

    {{--    @yield('pre_css')--}}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body class="login">

<div class="center-me">
    @yield('content')

</div>
@yield('post_js')
</body>
</html>
