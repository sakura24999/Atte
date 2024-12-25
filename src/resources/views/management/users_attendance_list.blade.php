@extends('layouts.app')

@section('header')
<nav class="users_attendance_list-header">

    <body id="attendance-page">
        <div class="users-header-left">
            <h5 class="header-title">Atte</h5>
        </div>
    </body>
    <ul class="users-header-right">
        <li class="header-right-link"><a href="{{route('users_attendance_list')}}" class="users_list-link">登録ユーザー一覧</a></li>
    </ul>
</nav>
@endsection

@section('main')
<div class="users_attendance_list-container">
    <table class="attendance-table">
        <h3 class="users_attendance_list-title">登録ユーザー勤怠記録</h3>
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
                <td>{{$attendance->user->last_name}}{{$attendance->user->first_name}}</td>
                <td>{{$attendance->start_time ? Carbon\Carbon::parse($attendance->start_time)->format('H:i:s') : '-'}}</td>
                <td>{{$attendance->end_time ? Carbon\Carbon::parse($attendance->end_time)->format('H:i:s') : '-'}}</td>
                <td>{{ $attendance->break_time ? sprintf('%02d:%02d:%02d',
                    floor($attendance->break_time / 3600),
                    floor(($attendance->break_time % 3600) / 60),
                    $attendance->break_time % 60) : '00:00:00' }}</td>
                <td>{{$attendance->total_work_time ? sprintf('%02d:%02d:%02d', floor($attendance->total_work_time / 3600),floor(($attendance->total_work_time % 3600) / 60),
                $attendance->total_work_time % 60) : '00:00:00'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-container">
        {{$attendances->links()}}
    </div>
</div>
@endsection

@section('footer')

@endsection