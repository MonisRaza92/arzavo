<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body style="background: {{ $customizes['background_color'] ?? 'fafafa' }} !important;">
    {{-- Alerts --}}
    <x-alert />
    <main class="relative">
        @yield('content')
    </main>
</body>

</html>