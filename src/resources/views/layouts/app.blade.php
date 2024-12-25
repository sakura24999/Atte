<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/app.css')}}"

        </head>

<body>
    <header>@section('header')
        <h2>Atte</h2>
        @show
    </header>

    <main>@yield('main')
    </main>

    <footer>
        @yield('footer')
        <h6>Atte, inc.</h6>

    </footer>

</body>

</html>