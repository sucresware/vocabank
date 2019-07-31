<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection ('title')
        @yield('title') - VocaBank
        @else
        VocaBank
        @endif
    </title>
    <meta name="description"
        content="VocaBank - C'est comme RisiBank, sauf qu'on a échangé les stickers par des samples">

    @stack('head')

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('/apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('/apple-touch-icon-152x152.png') }}">
    <link rel="icon" type="image/png" href="{{ url('/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ url('/favicon-16x16.png') }}" sizes="16x16">
    <meta name="application-name" content="VocaBank">
    <meta name="theme-color" content="#3b4252">
    <meta name="msapplication-TileColor" content="#3b4252">
    <meta name="msapplication-TileImage" content="{{ url('/mstile-144x144.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" />
    @stack('css')

</head>

@yield('body')

</html>
