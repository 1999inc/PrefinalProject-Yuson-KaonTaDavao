<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kaon Ta! Davao – Yuson's Food Guide</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-amber-50 text-stone-800 antialiased">
    @include('partials.nav')
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.flash')
        @yield('content')
    </main>
    <footer class="border-t border-amber-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 text-sm text-stone-500 sm:px-6 lg:px-8">
            Kaon Ta! Davao · Your guide to local eats
        </div>
    </footer>
</body>
</html>
