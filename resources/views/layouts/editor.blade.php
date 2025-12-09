<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body id="builder-editor-body">
    {{-- Alerts --}}
    <x-alert />
    {{-- Main content --}}
    @yield('content')
</body>

</html>