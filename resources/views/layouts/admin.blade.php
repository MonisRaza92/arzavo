<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
</head>

<body data-barba="wrapper">
    <div data-barba="container" data-barba-namespace="{{ last(explode('.', request()->route()->getName())) }}">
        {{-- Navbar --}}
        @include('tenant.admin.includes.navbar')
        {{-- Sidebar --}}
        @include('tenant.admin.includes.sidebar')
        {{-- Alerts --}}
        <x-alert />
        {{-- Main content --}}
        <main class="main-admin-content relative lg:ml-[260px]! p-4">
            @include('tenant.admin.includes.breadcrumb')
            @yield('content')
        </main>
    </div>
</body>

</html>