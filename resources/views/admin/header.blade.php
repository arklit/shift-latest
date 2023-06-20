<link href="/css/admin.css" rel="stylesheet">
@php
    $isLoginPage = request()->getRequestUri() === '/admin/login';
        $logo = $isLoginPage ? '/assets/img/admin/logo-black.svg' : '/assets/img/admin/logo-white.svg';
        $additionalClass = $isLoginPage ? ' mb-4 row justify-content-center' : '';
        $width = $isLoginPage ? 125 : 250;
        $height = $isLoginPage ? 90 : 125;
@endphp
@if($isLoginPage)
    <a href="/admin/main" class="logo-admin-container{{ $additionalClass }}"><img class="admin-logo" src="{{ $logo }}" width="{{ $width }}" height="{{ $height }}"></a>
@else
    <a href="/admin/main" class="logo-admin-container{{ $additionalClass }}"><img class="admin-logo" src="{{ $logo }}" width="{{ $width }}" height="{{ $height }}"></a>
@endif
<p class="h2 n-m font-thin v-center">
    <span class="m-l d-none d-sm-block">
    </span>
</p>

{{-- \vendor\orchid\platform\resources\views\header.blade.php--}}
