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
    </ul>
</nav>
</div>
@endsection

@section('main')
<div class="container">
    <div class="date-navigation">
        <a href="{{route('attendance.index', ['date' => $previousDay])}}" class="date-nav-btn">&lt;</a>

        <span class="current-date">{{$date->format('Y-m-d')}}</span>
        <a href="{{route('attendance.index', ['date' => $nextDay])}}" class="">&gt;</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>勤務開始</th>
                <th>勤務終了</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
            <tr>
                <td>{{auth()->user()->last_name}}{{auth()->user()->first_name}}</td>
                <td>{{$attendance->start_time ? Carbon\Carbon::parse($attendance->start_time)->format('H:i:s') : '-'}}</td>
                <td>{{$attendance->end_time ? Carbon\Carbon::parse($attendance->end_time)->format('H:i:s') : '-'}}</td>
                <td>{{$attendance->break_time ? gmdate('H:i:s', $attendance->break_time) : '00:00:00'}}</td>
                <td>{{$attendance->total_work_time ? sprintf('%02d:%02d:%02d',
            floor($attendance->total_work_time / 3600),floor            (($attendance->total_work_time % 3600) / 60),
            $attendance->total_work_time % 60
        ) : '00:00:00'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $attendances->appends(['date' => $date->format('Y-m-d')])->links()}}
    </div>
</div>
@endsection