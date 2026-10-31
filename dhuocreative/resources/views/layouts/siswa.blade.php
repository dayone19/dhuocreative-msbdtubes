<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhuoCreative Portal</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @vite([
        'resources/css/sidebar.css', 'resources/css/siswa.css', 'resources/js/app.js'
    ])
</head>

<body>
    @include('components.sidebarS')

    <main class="main-content">
        @yield('content')
    </main>

</body>
</html>