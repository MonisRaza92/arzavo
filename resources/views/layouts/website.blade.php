<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper" style="background: {{ $customizes['background_color'] ?? 'fafafa' }} !important;">
    {{-- Alerts --}}
    <x-alert />
    <main class="relative" data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        @yield('content')
    </main>
    {{-- Footer --}}
    @include('includes.footer')
</body>

</html>