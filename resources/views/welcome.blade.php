<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @php
             $commonTitle = 'template';
             $commonDescription = 'descr';

//                 if (!empty($seo) && !empty($seo->getTitle())) {
//                      $commonTitle = $seo->getTitle();
//                  } elseif (View::hasSection('title')) {
//                      $commonTitle = trim(View::yieldContent('title')) . " | WANNGO - интернет-магазин обуви оптом";
//                  }
//                  if (!empty($titlePage) && $titlePage !== 1) {
//                      $commonTitle .= ' | Страница ' . $titlePage;
//                  }
//
//
//                  if(!empty($seo) && !empty($seo->getDescription())) {
//                      $commonDescription = $seo->getDescription();
//                  } elseif (View::hasSection('description')) {
//                      $commonDescription = trim(View::yieldContent('description'));
//                  }

        @endphp
        <title>{{$commonTitle}}</title>
        <meta name="description" content="{{$commonDescription}}">
        @vite('resources/css/app.scss')
        </style>
    </head>
    <body>
        <div class="page">
            @include('components.header')
            @yield('content')
            @include('components.footer')
        </div>
        @vite(['resources/js/app.js'])
    </body>

</html>
