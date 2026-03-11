<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
    @vite('resources/js/builder.js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
</head>

<body id="builder-editor-body">
    {{-- Alerts --}}
    <x-alert />
    <x-content-model />
    {{-- Main content --}}
    @yield('content')
    <script>
        window.ARZAVO_EDITOR_MODE = true;
    </script>
</body>

</html>