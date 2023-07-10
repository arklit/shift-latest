@extends('mails.layout')
@section('content')
    Уважаемый {{ $customer->name }} {{ $customer->patronymic }}, Вы успешно зарегистрировались!

    Ваш пароль для входа в ЛК: {{ $password }}
@endsection
