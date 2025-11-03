<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper">
    <div data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        {{-- Navbar --}}
        @include('admin.includes.navbar')
        {{-- Sidebar --}}
        @include('admin.includes.sidebar')
        {{-- Alerts --}}
        <x-alert />
        {{-- Main content --}}
        <main class="main-admin-content relative lg:ml-[260px]! px-4" style="padding-top: calc(var(--logo-size) + 36px);">
            @include('includes.breadcrumb')
            @yield('content')
        </main>
    </div>
    {{-- Footer --}}
    @include('includes.footer')
</body>

</html>