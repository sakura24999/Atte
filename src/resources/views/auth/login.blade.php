@extends('layouts.app')


@section('main')
<div class="login-information">
    <h4 class="login-title">ログイン</h4>

    <form action="{{route('login')}}" method="POST">
        @csrf
        @if ($errors->any())
        <div class="login-error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="success-message"><span class="success-message-text">{{session('success')}}</span></div>

        @endif

        <input type="email" id="email" class="login-email" name="email" placeholder="メールアドレス" value="{{old('email')}}">
        <input type="password" id="password" class="login-password" name="password" placeholder="パスワード">
        <button type="submit" class="login-button">ログイン</button>
    </form>
    <div class="register-link">
        <p class="A-text">アカウントをお持ちでない方はこちらから</p>
        <a href="{{route('register')}}" class="register-link-text">会員登録</a>
    </div>
</div>
@endsection
