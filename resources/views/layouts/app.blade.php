<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper">
    <div data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        <x-alert />
        {{-- Main content --}}
        <main>
            @yield('content')
        </main>
    </div>
    {{-- Footer --}}
    @include('includes.footer')
</body>

</html>