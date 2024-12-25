@extends('layouts.app')

@section('main')
<h4 class="register-title">会員登録</h4>

<form action="{{route('register')}}" class="register-information" method="POST">
    @csrf
    @if ($errors->any())
    <div class="register-error-messages">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>

    @endif

    <input type="text" id="name" class="register-name" name="name" placeholder="名前" value="{{old('name')}}">
    <input type="email" id="email" class="register-email" name="email" placeholder="メールアドレス" value="{{old('email')}}">
    <input type="password" id="password" class="register-password" placeholder="パスワード" name="password">
    <input type="password" id="password" class="register-confirmation" placeholder="確認用パスワード" name="password_confirmation">
    <button type="submit" class="register-button">会員登録</button>
    <p class="login-link">アカウントをお持ちの方はこちらから</p>
    <a href="{{route('login')}}" class="login-link-text">ログイン</a>
</form>

@endsection
