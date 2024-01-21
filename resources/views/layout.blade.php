<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @include('components.common.meta')
    @vite('resources/scss/client/app.scss')
</head>
<body>
    <div id="app">
        <form-builder name="form1"></form-builder>
        <form-builder name="form2"></form-builder>
        <form-builder name="system"></form-builder>
        <div class="page">
            @include('components.header')
            @yield('content')
            @include('components.common.seo-text-block')
            @include('components.footer')
        </div>
    </div>

@vite(['resources/js/client/app.js'])

</body>
</html>
