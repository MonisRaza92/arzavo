<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body>
    <x-alert />
    {{-- Main content --}}
    <main>
        @yield('content')
    </main>
</body>

</html>