@extends('layouts.app')

@section('header')
<div class="header-users_list-nav">
    <nav class="header-users_attendance_list">

        <body id="users-list-page">
            <div class="users-header-left">
                <h5 class="header-title">Atte</h5>
            </div>
        </body>
        <ul class="users-header-right">
            <li class="header-right-link">
                <a href="{{route('users_list')}}">登録ユーザー勤怠記録</a>
            </li>
        </ul>
    </nav>
</div>
@endsection

@section('main')
<div class="users-container">
    <div class="users_list_arrangement">
        <table class="attendance-table">
            <thead>
                <h3 class="users_list_title">登録ユーザー一覧</h3>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->first_name}}{{$user->last_name}}</td>
                    <td>{{$user->email}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-container">
        {{$users->links()}}
    </div>
</div>

@endsection

@section('footer')

@endsection