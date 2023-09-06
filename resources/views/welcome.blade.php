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

                 if (!empty($seo) && !empty($seo->getTitle())) {
                      $commonTitle = $seo->getTitle();
                  } elseif (View::hasSection('title')) {
                      $commonTitle = trim(View::yieldContent('title')) . " | WANNGO - интернет-магазин обуви оптом";
                  }
                  if (!empty($titlePage) && $titlePage !== 1) {
                      $commonTitle .= ' | Страница ' . $titlePage;
                  }

                  if(!empty($seo) && !empty($seo->getDescription())) {
                      $commonDescription = $seo->getDescription();
                  } elseif (View::hasSection('description')) {
                      $commonDescription = trim(View::yieldContent('description'));
                  }

        @endphp
        <title>{{$commonTitle}}</title>
        <meta name="description" content="{{$commonDescription}}">

        @vite('resources/css/app.scss')
    </head>
    <body>
        <div class="page">
            @include('components.header')

            <div class="container">
                <div class="grid">
                    <div class="box"></div>
                    <div class="box"></div>
                    <div class="box"></div>
                    <div class="box"></div>
                    <div class="box"></div>
                    <div class="box"></div>
                    <div class="box"></div>
                </div>
            </div>

            <form action="" class="form">
                <div class="item">
                    <label for="">asdas</label>
                    <input class="input" type="text" id="input-1" name="name"/>
                    <span id="name_error" class="error"></span>
                </div>
                <div class="item">
                    <label for="">asdas</label>
                    <input class="input" type="text" id="input-2" name="phone"/>
                    <span id="phone_error" class="error"></span>
                </div>
                <div class="item">
                    <label for="">asdas</label>
                    <input class="input" type="text" id="input-3" name="msg"/>
                    <span id="msg_error" class="error"></span>
                </div>
                <button type="submit">отправить</button>
            </form>
            @yield('content')
            @if(!empty($seo) && !empty($seo->seo_description))
                @include('components.seo.seo', ['seo_title' => $seo->seo_title, 'seo_text' => $seo->seo_description, 'image' => $seo->image])
            @endif
            @include('components.footer')
        </div>
        @vite(['resources/js/app.js'])
    </body>

</html>
