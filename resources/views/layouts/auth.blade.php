<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper">
    <div data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        {{-- Main content --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    {{-- Footer --}}
    @include('includes.footer')
</body>

</html>