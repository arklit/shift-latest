@extends('layout')
@section('content')
    @include('components.breadcrumbs')
    <div class="container">
        {{ $page->content }}
    </div>
@endsection
