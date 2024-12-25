@extends('layouts.app')

@section('main')
<div class="verify-email-container">
    <div class="logo">Atte</div>
    <div class="message">
        アカウント登録ありがとうございます。<br>
        開始する前に、メールアドレスの確認をお願いいたします。<br>
        確認メールが届いていない場合は、再送信ボタンをクリックしてください。
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="success-message">
        確認用のメールを再送信しました。
    </div>
    @endif

    <div class="button-container">
        <form action="{{route('verification.send')}}" method="POST">
            @csrf
            <button type="submit" class="resend-button">
                確認メールを再送信
            </button>
        </form>

        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit" class="email-logout-button">ログアウト</button>
        </form>
    </div>
</div>
@endsection
