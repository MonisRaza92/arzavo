<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
</head>

<body>
    {{-- Navbar --}}
    @include('arzavo.admin.includes.navbar')
    {{-- Sidebar --}}
    @include('arzavo.admin.includes.sidebar')
    {{-- Alerts --}}
    <x-alert />
    {{-- Main content --}}
    <main class="main-admin-content relative lg:ml-[260px]! p-4">
        @yield('content')
    </main>
</body>

</html>