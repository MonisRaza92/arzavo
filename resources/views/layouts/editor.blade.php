<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper">
    {{-- Alerts --}}
    <x-alert />
    <div data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        {{-- Main content --}}
        @yield('content')
    </div>
    {{-- Footer --}}
    @include('includes.footer')
</body>

</html>