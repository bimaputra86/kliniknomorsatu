<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Klinik Nomor Satu</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/img/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/img/icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-teal-500 selection:text-white">
    @yield('content')
</body>
</html>
