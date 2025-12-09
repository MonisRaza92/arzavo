<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body>
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
</body>

</html>