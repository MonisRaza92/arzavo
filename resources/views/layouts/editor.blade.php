<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
    @vite('resources/js/builder.js')
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
