<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhuoCreative Portal</title>

    @vite([ 'resources/css/sidebar.css', 'resources/css/tentor.css', 'resources/js/app.js' ])
</head>

<body>
    @include('components.sidebarT')

    <main class="main-content">

        @yield('content')

    </main>

</body>
</html>