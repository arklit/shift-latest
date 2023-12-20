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
<form id="myForm">
    @csrf
    <input type="text" name="name" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Отправить</button>
</form>

<form id="myForm2">
    @csrf
    <input type="text" name="name" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Отправить</button>
</form>

<script>
    document.getElementById('myForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        fetch('/ajax/forms/test/send', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                console.log(data);
                // Обработка ответа от сервера
            })
            .catch(function(error) {
                console.error(error);
                // Обработка ошибок
            });
    });

    document.getElementById('myForm2').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        fetch('/ajax/forms/test2/send', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                console.log(data);
                // Обработка ответа от сервера
            })
            .catch(function(error) {
                console.error(error);
                // Обработка ошибок
            });
    });
</script>
    <div class="page">
        @include('components.header')
        @yield('content')
        @include('components.common.seo-text-block')
        @include('components.footer')
    </div>
    @vite(['resources/js/client/app.js'])
</body>
</html>
