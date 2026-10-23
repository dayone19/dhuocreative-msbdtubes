<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhuoCreative Portal</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Anton+SC&family=Bebas+Neue&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Libre+Caslon+Condensed:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

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