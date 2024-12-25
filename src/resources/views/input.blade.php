@extends('layouts.app')

@section('header')
<nav class="header-nav">
    <div class="header-left">
        <h5 class="header-title">Atte</h5>
    </div>
    <ul class="header-menu">
        <li><a href="{{route('input')}}">ホーム</a></li>
        <li><a href="{{route('attendance.index')}}">日付一覧</a></li>
        <li>
            <form action="{{route('logout')}}" method="POST">
                @csrf
                <button type="submit" class="logout-button">ログアウト</button>
            </form>
        </li>
    </ul>
</nav>
@endsection

@section('main')
<div class="input-container">
    <div class="user-greeting">
        <h2>{{auth()->user()->last_name}}{{auth()->user()->first_name}}さんお疲れ様です！</h2>
    </div>

    @if (session('success'))
    <div class="alert-success">{{session('success')}}</div>
    @endif

    @if (session('error'))
    <div class="alert-error">{{session('error')}}</div>
    @endif

    <div class="button-grid">
        <div class="button-card">
            <form action="{{route('attendance.clockIn')}}" method="POST">
                @csrf
                <button type="submit" {{$buttonStates['workStartButton'] ? '': 'disabled'}}>勤務開始</button>
            </form>
        </div>

        <div class="button-card">
            <form action="{{route('attendance.clockOut')}}" method="POST">
                @csrf
                <button type="submit" {{$buttonStates['workEndButton'] ? '': 'disabled'}}>勤務終了</button>
            </form>
        </div>

        <div class="button-card">
            <form action="{{route('attendance.breakStart')}}" method="POST">
                @csrf
                <button type="submit" {{$buttonStates['breakStartButton'] ? '' : 'disabled'}}>休憩開始</button>
            </form>
        </div>

        <div class="button-card">

            <form action="{{route('attendance.breakEnd')}}" method="POST">
                @csrf
                <button type="submit" {{$buttonStates['breakEndButton'] ? '' : 'disabled'}}>休憩終了</button>
            </form>
        </div>
    </div>
</div>
@endsection
